<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use App\User;
use App\Message;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMailable;
use App\Actionlog;
use App\Member;
use Carbon\Carbon;
use App\MemberPackages;
use App\Payment;
use DB;
use Curl;
use App\Smssetting;
use App\Emailsetting;
use App\Notification;
use App\Notificationmsgdetails;
use App\Emailnotificationdetails;

class Reminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */

    
    public function handle(Request $request)
    {
            $action = new Actionlog();
            $action->user_id = session()->get('admin_id');
            $action->ip = $request->ip();
            $action->action_type = 'Cron Job';
            $action->action = 'Notification Reminder Message Cron Job By System';
            $action->save();
            $todays =  Carbon::now()->toDateString(); 
            $member = Member::whereIn('status',[1,2])->get()->all();
            $today =  Carbon::now();
          
            $bithday_msg = Message::where('messagesid','3')->get()->first();
            $anniversary_msg= Message::where('messagesid','4')->get()->first();
            $duedate_msg = Message::where('messagesid','19')->get()->first();
            $package_expiry_msg= Message::where('messagesid','21')->get()->first();

            $bithday_msg = $bithday_msg->message;
            $anniversary_msg = $anniversary_msg->message;
            $package_expiry_msg = $package_expiry_msg->message;
            $duedate_msg = $duedate_msg->message;


            $memberpackage = MemberPackages::leftjoin('member','member.userid','=','memberpackages.userid')
                            ->join('notification','notification.mobileno','=','member.mobileno')
                            ->join('schemes','memberpackages.schemeid','=','schemes.schemeid') 
                            ->whereIn('memberpackages.status',[1,3])
                            ->select('memberpackages.*','member.*','schemes.*','member.mobileno as mmobileno','notification.*','member.email as memail')
                            ->get()
                            ->all();
                       

            $emailsetting =  Emailsetting::where('status',1)->get()->first();

        foreach ($memberpackage as  $mp) {

                $datetime1 = date_create($todays); 
                $datetime2 = date_create($mp->expiredate);
                $interval = date_diff($datetime1, $datetime2);
                 $interval = $interval->format('%R%a');
   
      

                     $fname = ucfirst($mp->firstname);
                     $lname = ucfirst($mp->lastname);
                     $mobileno = $mp->mobileno;
                     $date = $mp->expiredate;
                     $dnd  = $mp->sms;
                     $dndmpemail = $mp->memail;
                     $packagename = $mp->schemename;
                     $date = date("d-m-Y", strtotime($date));

                     $package_expiry_msg2 = str_replace(array('[FirstName]','[LastName]','[packgename]','[date]'),array($fname, $lname,$packagename,$date),$package_expiry_msg);
                   
                   $package_expiry2 = $package_expiry_msg2;

                if ($interval == -2 || $interval == -1 || $interval == 0 || $interval == 1 || $interval == 2 || $interval == 3 || $interval == 7) {

                    $package_expiry = urlencode($package_expiry_msg2);
               
                    if ($mp->sms == 1) {
                       

                        $smssetting = Smssetting::where('status',1)->where('smsonoff','Active')->get()->first();
                     
                    
                         if ($smssetting) {

                              $u = $smssetting->url;
                              $url= str_replace('$mobileno', $mobileno, $u);
                              $url=str_replace('$msg', $package_expiry, $url);


                               $otpsend = Curl::to($url)->get();

                              $action = new Notificationmsgdetails();
                              $action->user_id = session()->get('admin_id');
                              $action->mobileno = $mobileno;
                              $action->smsmsg = $package_expiry2;
                              $action->smsrequestid = $otpsend;
                              $action->subject = 'Package Expiry Message By System Before '.$interval.' days';
                              $action->save();

                          }
                       }


                    if ($emailsetting) {
                        if ($dndmpemail) {
                          if ($mp->email == 1) {

                                 $data = [
                                         
                                                 'msg' => $package_expiry2,
                                                 'mail'=> $dndmpemail,
                                                 'subject' => $emailsetting->hearder,
                                                 'senderemail'=> $emailsetting->senderemailid,
                                              ];

                                    

                                        Mail::send('admin.name', ["data1"=>$data], function($message) use ($data){

                                              $message->from($data['senderemail'], 'Package Expiry Message');
                                              $message->to($data['mail']);
                                              $message->subject($data['subject']);

                                        });

                                        $action = new Emailnotificationdetails();
                                        $action->user_id = session()->get('admin_id');
                                        $action->mobileno = $mobileno;
                                        $action->message = $package_expiry2;
                                        $action->emailform = $data['senderemail'];
                                        $action->emailto = $data['mail'];
                                        $action->subject = $data['subject'];
                                        $action->messagefor = 'Package Expiry Mail By System Before '.$interval.' Days';
                                        $action->save();

                         }
                      }
                   } 

               }

            }
    


                $due = Payment::join('member','member.userid','=','payments.userid')
                        ->join('notification','notification.mobileno','=','member.mobileno')->where('payments.duedate','!=',null)->where('payments.schemeid','!=',0)
                        ->select(DB::raw('MAX(paymentid) as pid'))
                        ->groupBy('payments.userid')
                        ->get()
                        ->all();

                

                $pid=array();

                for($i=0;$i<count($due);$i++)
                {
                    $pid[]=$due[$i]['pid'];
                }
               
                $due = Payment::leftjoin('member','member.userid','=','payments.userid')
                        ->join('notification','notification.mobileno','=','member.mobileno')
                       
                         ->join('schemes','payments.schemeid','=','schemes.schemeid')
                        ->select('payments.*','member.email as memail','member.*','notification.*')
                        ->whereIn('payments.paymentid',$pid)
                       
                        ->where('duedate','!=',null)
                        ->where('payments.schemeid','!=',0)
                        ->get()
                        ->all();
 


                    foreach ($due as $u) {

                   

                     $datetime1 = date_create($todays); 
                     $datetime2 = date_create($u->duedate); 
                     $interval = date_diff($datetime1, $datetime2);
                     $interval = $interval->format('%R%a');
                       

                     $fname = $u->firstname;
                     $lname = $u->lastname;
                     $mobileno = $u->mobileno;
                     $date = $u->duedate;
                     $dnd  = $u->sms;
                     $dndemail = $u->email;
                     $membermail = $u->memail;
                     $amount = $u->remainingamount;
                     $packagename = $u->schemename;
                     $pkgassigndate = date("d-m-Y", strtotime($u->date));;
                      $date = date("d-m-Y", strtotime($date));



                    

                     $duedate_msg_2 = str_replace(array('[FirstName]','[LastName]','[packgename]','[date_of_packge_assign]','[amount]','[date]'),array($fname, $lname, $packagename, $pkgassigndate, $amount, $date) ,$duedate_msg);
                          // echo "".$interval."<br>";

                     if ($interval == -2 || $interval == -1 || $interval == 0 || $interval == 1 || $interval == 2 || $interval == 3 || $interval == 7) {

                         $duedate_msg_send = urlencode($duedate_msg_2);

                       

                        if ($u->sms == 1) {

                         
                         $smssetting = Smssetting::where('status',1)->where('smsonoff','Active')->first();

                         if ($smssetting) {

                              $u = $smssetting->url;
                              $url= str_replace('$mobileno', $mobileno, $u);
                              $url=str_replace('$msg', $duedate_msg_send, $url);

                             


                              $otpsend = Curl::to($url)->get();

                              $action = new Notificationmsgdetails();
                              $action->user_id = session()->get('admin_id');
                              $action->mobileno = $mobileno;
                              $action->smsmsg = $duedate_msg_2;
                              $action->smsrequestid = $otpsend;
                              $action->subject = 'DueDate Message By System Befor '.$interval.' days';
                              $action->save();

                            }
                        }
                       

                      if ($emailsetting) {
                        if ($dndemail) {
                          if ($dndemail == 1) {

                                 $data = [
                                                
                                                 'msg' => $duedate_msg_2,
                                                 'mail'=> $membermail,
                                                 'subject' => $emailsetting->hearder,
                                                 'senderemail'=> $emailsetting->senderemailid,
                                              ];

  

                                        Mail::send('admin.name', ["data1"=>$data], function($message) use ($data){

                                              $message->from($data['senderemail'], 'DueDate Message');
                                              $message->to($data['mail']);
                                              $message->subject($data['subject']);

                                        });

                                        $action = new Emailnotificationdetails();
                                        $action->user_id = session()->get('admin_id');
                                        $action->mobileno = $mobileno;
                                        $action->message = $duedate_msg_2;
                                        $action->emailform = $data['senderemail'];
                                        $action->emailto = $data['mail'];
                                        $action->subject = $data['subject'];
                                        $action->messagefor = 'DueDate Expiry Mail By System Before '.$interval.' Days';
                                        $action->save();

                         }
                      }
                   }
                }    
             }


    $member_birthday =Member::leftjoin('notification','notification.mobileno','=','member.mobileno')
                        ->where('member.status',1)
                        ->select('member.status','member.anniversary','member.birthday','notification.sms','notification.email','member.email as memail','member.firstname','member.lastname','member.mobileno')
                        ->whereDay('birthday','=',$today->format('d'))
                        ->whereMonth('birthday','=',$today->format('m'))
                        ->get()
                        ->all();
                        // dd($member_birthday);

    $member_anniversary =Member::leftjoin('notification','notification.mobileno','=','member.mobileno')
                        ->where('member.status',1)
                        ->select('member.status','member.anniversary','member.birthday','notification.sms','notification.email','member.email as memail','member.firstname','member.lastname','member.mobileno')
                        ->whereDay('member.anniversary','=',$today->format('d'))
                        ->whereMonth('member.anniversary','=',$today->format('m'))
                        ->get()
                        ->all();
                 


     $msg= DB::table('messages')->where('messagesid','3')->get()->first();    
            
            if ($member_birthday) {

                foreach ($member_birthday  as  $mb) {

                    
                    $fname = ucfirst($mb->firstname);
                    $lname = ucfirst($mb->lastname);
                    $mobileno = $mb->mobileno;
                    $bemail = $mb->memail;
                   
                    $bithday_msg_new =$msg->message;
                    $bithday_msg_new = str_replace("[FirstName]",$fname,$bithday_msg_new);
                    $bithday_msg_new= str_replace("[LastName]",$lname,$bithday_msg_new);
                    $bithday_msg_new2 = $bithday_msg_new;
                    $bithday_msg_new = urlencode($bithday_msg_new);

                    // print_r($bithday_msg_new2);echo "<br/>";


                    if ($mb->sms == 1) {

                        $smssetting = Smssetting::where('status',1)->where('smsonoff','Active')->first();

                        if ($smssetting) {

                        $u = $smssetting->url;
                        $url= str_replace('$mobileno', $mobileno, $u);
                        $url=str_replace('$msg', $bithday_msg_new, $url);

                        $otpsend = Curl::to($url)->get();

                        $action = new Notificationmsgdetails();
                        $action->user_id = session()->get('admin_id');
                        $action->mobileno = $mobileno;
                        $action->smsmsg = $bithday_msg_new2;
                        $action->smsrequestid = $otpsend;
                        $action->subject = 'Bithday Massage';
                        $action->save();
                    }
                  }

              

                    if ($mb->email == 1) {

                        $data = [
                                 //'data' => 'Rohit',
                                 'msg' => $bithday_msg_new2,
                                 'mail'=> $bemail,
                                 'subject' => $emailsetting->hearder,
                                 'senderemail'=> $emailsetting->senderemailid,
                              ];


                        Mail::send('admin.name', ["data1"=>$data], function($message) use ($data){

                              $message->from($data['senderemail'], 'Birthday Message');
                              $message->to($data['mail']);
                              $message->subject($data['subject']);

                        });

                        $action = new Emailnotificationdetails();
                        $action->user_id = session()->get('admin_id');
                        $action->mobileno = $mobileno;
                        $action->message = $bithday_msg_new2;
                        $action->emailform = $data['senderemail'];
                        $action->emailto = $data['mail'];
                        $action->subject = $data['subject'];
                        $action->messagefor = 'Birthday Message By System';
                        $action->save();
                        
                    }

            }
               
        }

            
    $msg= DB::table('messages')->where('messagesid','4')->get()->first();

            if ($member_anniversary) {

                foreach ($member_anniversary  as  $ma) {

                     $fname = ucfirst($ma->firstname);
                     $lname = ucfirst($ma->lastname);
                     $mobileno = $ma->mobileno;
                     $aemail = $ma->memail;

                     

                    
                    $anniversary_msg_new =$msg->message;
                    $anniversary_msg_new = str_replace("[FirstName]",$fname,$anniversary_msg_new);
                    $anniversary_msg_new= str_replace("[LastName]",$lname,$anniversary_msg_new);
                    $anniversary_msg_new2 = $anniversary_msg_new;
                    $anniversary_msg_new = urlencode($anniversary_msg_new);



                    if ($ma->sms == 1) {

                  

                        $smssetting = Smssetting::where('status',1)->where('smsonoff','Active')->first();
                        if ($smssetting) {
                        $u = $smssetting->url;
                        $url= str_replace('$mobileno', $mobileno, $u);
                        $url=str_replace('$msg', $anniversary_msg_new, $url);

                        // print_r($url);echo "<br/>";

                        $otpsend = Curl::to($url)->get();

                        $action = new Notificationmsgdetails();
                        $action->user_id = session()->get('admin_id');
                        $action->mobileno = $mobileno;
                        $action->smsmsg = $anniversary_msg_new2;
                        $action->smsrequestid = $otpsend;
                        $action->subject = 'Anniversary Message By System';
                        $action->save();

                    }
                      
                  }

                    if ($ma->email == 1) {

                        $data = [
                             
                                 'msg' => $anniversary_msg_new2,
                                 'mail'=> $aemail,
                                 'subject' => $emailsetting->hearder,
                                 'senderemail'=> $emailsetting->senderemailid,
                              ];

                   

                        Mail::send('admin.name', ["data1"=>$data], function($message) use ($data){

                              $message->from($data['senderemail'], 'Anniversary Message');
                              $message->to($data['mail']);
                              $message->subject($data['subject']);

                        });

                        $action = new Emailnotificationdetails();
                        $action->user_id = session()->get('admin_id');
                        $action->mobileno = $mobileno;
                        $action->message = $anniversary_msg_new2;
                        $action->emailform = $data['senderemail'];
                        $action->emailto = $data['mail'];
                        $action->subject = $data['subject'];
                        $action->messagefor = 'Anniversary Message By System';
                        $action->save();

                    }

                }
               
               } 
               echo 'success';
          
        }


    }
