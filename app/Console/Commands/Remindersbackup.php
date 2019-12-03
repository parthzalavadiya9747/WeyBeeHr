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

class Remindersbackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:reminders';

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

    
    public function handle()
    {
       $action = new Actionlog();
            $action->user_id = session()->get('admin_id');
            $action->ip = $request->ip();
            $action->action_type = 'Cron Job';
            $action->action = 'Notification Reminder Message Cron Job By System';
            $action->save();


            $todays =  Carbon::now()->toDateString(); 
            $member = Member::get()->all();
            $today = Carbon::now();

            $bithday_msg = Message::where('messagesid','3')->get()->first();
            $anniversary_msg= Message::where('messagesid','4')->get()->first();
            $duedate_msg = Message::where('messagesid','19')->get()->first();
            $package_expiry_msg= Message::where('messagesid','20')->get()->first();

            $bithday_msg = $bithday_msg->message;
            $anniversary_msg = $anniversary_msg->message;
            $package_expiry_msg = $package_expiry_msg->message;
            $duedate_msg = $duedate_msg->message;

              // dd($duedate_msg);

            $memberpackage = MemberPackages::leftjoin('member','member.userid','=','memberpackages.userid')
                            ->leftjoin('notification','notification.mobileno','=','member.mobileno')
                            ->leftjoin('schemes','memberpackages.schemeid','=','schemes.schemeid')   
                            ->whereDate('memberpackages.expiredate', '>=', $todays)
                            ->select('memberpackages.*','member.*','schemes.*','member.mobileno as mmobileno','notification.*','member.email as memail')
                            ->get()
                            ->all();


                            

            $emailsetting =  Emailsetting::where('status',1)->first();



        foreach ($memberpackage as  $mp) {

                    // print_r($mp->firstname);
   
                //$date = Carbon::createFromFormat('Y-m-d H:s:i', ''.$mp->expiredate.'0:00:00');

                // $m_mobileno = Member::where('userid',$mp->userid)->select('mobileno','email')->first();
                // $dnd = Notification::where('mobileno','=',$m_mobileno->mobileno)->get()->first();
                   
                //$diff_in_days = $todays->diffInDays($date);

                $datetime1 = date_create($todays); 
                $datetime2 = date_create($mp->expiredate);
                $interval = date_diff($datetime1, $datetime2);
                $interval = $interval->format('%R%a days');
                 //print_r($interval); echo "<br/>";
                   echo "<br/>";

                   
                  // print_r($diff_in_days);echo "&nbsp;&nbsp;&nbsp;"; print_r($u->memberpackagesid);
                  // echo "<br/>";
                  //$to = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', '2015-5-5 3:30:34');
                  //$from = \Carbon\Carbon::createFromFormat($users);

                  // print_r($interval);

                     $fname = $mp->firstname;
                     $lname = $mp->lastname;
                     $mobileno = $mp->mobileno;
                     $date = $mp->expiredate;
                     $dnd  = $mp->sms;
                     $dndmpemail = $mp->memail;
                     $packagename = $mp->schemename;

                     //


                     // print_r($date);
 
                    //$package_expiry = str_replace(array('[FirstName]','[LastName]'),array($fname, $lname),$package_expiry_msg);


                         // print_r($package_expiry);echo "<br/>";


                    // $msg= DB::table('messages')->where('messagesid','18')->get()->first();
                    // $package_expiry =$msg->message;
                    $package_expiry_msg = str_replace("[FirstName]",$fname,$package_expiry_msg);
                    $package_expiry_msg= str_replace("[LastName]",$lname,$package_expiry_msg);
                    $package_expiry_msg= str_replace("[packgename]",$packagename,$package_expiry_msg);
                    $package_expiry_msg= str_replace("[date]",$date,$package_expiry_msg);
                    $package_expiry2 = $package_expiry_msg;

                    // dd($package_expiry_msg);

                    

                   
                      if ($mp->sms == 1) {
                      

                        if ($interval == 15 ) {

                            echo $mp->mobileno;

                            // $expirymsg = "Your Package Is Expire  After 15 days";
                            // echo "<br/>";

                            // $package_expiry= str_replace("[ExpiryMsg]",$expirymsg,$package_expiry);
                            $package_expiry = urlencode($package_expiry_msg);

                            $smssetting = Smssetting::where('status',1)->where('smsonoff','Active')->first();
                            if ($smssetting) {
                             
                            $u = $smssetting->url;
                            $url= str_replace('$mobileno', $mobileno, $u);
                            $url=str_replace('$msg', $package_expiry, $url);
                            // dd($url);

                            
                            // echo  $fname.''.$lname.'';
                          // echo  $msg;exit;

                           
                          $otpsend = Curl::to($url)->get();

                          $action = new Notificationmsgdetails();
                          $action->user_id = session()->get('admin_id');
                          $action->mobileno = $mobileno;
                          $action->smsmsg = $package_expiry2;
                          $action->smsrequestid = $otpsend;
                          $action->subject = 'Package Expiry Message By System Before 15 days';
                          $action->save();

                                //     $action = new Notificationmsgdetails();
                                //     $action->user_id = session()->get('admin_id');
                                //     $action->mobileno = $mp->mmobileno;
                                //     $action->smsmsg = $package_expiry;
                                //     $action->smsrequestid = $package_expiry_response;
                                //     $action->subject = 'Package Expiry Message By System befor 15 days';
                                //     $action->save();
                                     

                        }

                    }


                
                        if ($interval == 7) {

                          echo $mp->mobileno;

                            // $expirymsg = "Your Package Is Expire  After 7 days";
                            // echo "<br/>";

                           
                            // echo  $fname.''.$lname.'';

                          
                          // echo  $msg;exit;

                        // $package_expiry= str_replace("[ExpiryMsg]",$expirymsg,$package_expiry);
                            // $package_expiry2 = $package_expiry;
                            $package_expiry = urlencode($package_expiry_msg);
                         // exit;

                        $smssetting = Smssetting::where('status',1)->where('smsonoff','Active')->first();
                        if ($smssetting) {
                        $u = $smssetting->url;
                        $url= str_replace('$mobileno', $mobileno, $u);
                        $url=str_replace('$msg', $package_expiry, $url);
                        // echo  $url;exit;
                          

                          $otpsend = Curl::to($url)->get();

                          $action = new Notificationmsgdetails();
                          $action->user_id = session()->get('admin_id');
                          $action->mobileno = $mobileno;
                          $action->smsmsg = $package_expiry2;
                          $action->smsrequestid = $otpsend;
                          $action->subject = 'Package Expiry Message By System Before 7 days';
                          $action->save();
                      }

                        // $package_expiry_response = Curl::to('http://sms.weybee.in/api/sendapi.php?auth_key=2169KrEMnx2ZgAqSfavSSC&mobiles='.$mobileno.'&message='.$package_expiry.'&sender=PYOFIT&route=4')->get();

                        //             $action = new Notificationmsgdetails();
                        //             $action->user_id = session()->get('admin_id');
                        //             $action->mobileno = $mp->mmobileno;
                        //             $action->smsmsg = $package_expiry;
                        //             $action->smsrequestid = $package_expiry_response;
                        //             $action->subject = 'Package Expiry Message By System befor 7 days';
                        //             $action->save();
                    }
                    if ($interval == 3) {
                       echo $mp->mobileno;
                       // $expirymsg = "Your Package Is Expire  After 3 days";
                            // echo "<br/>";

                        // $package_expiry= str_replace("[ExpiryMsg]",$expirymsg,$package_expiry);
                            // $package_expiry2 = $package_expiry;
                            $package_expiry = urlencode($package_expiry_msg);

                            $smssetting = Smssetting::where('status',1)->where('smsonoff','Active')->first();
                            if ($smssetting) {
                        $u = $smssetting->url;
                        $url= str_replace('$mobileno', $mobileno, $u);
                        $url=str_replace('$msg', $package_expiry, $url);
                        // echo  $url;exit;
                          

                          $otpsend = Curl::to($url)->get();

                          $action = new Notificationmsgdetails();
                          $action->user_id = session()->get('admin_id');
                          $action->mobileno = $mobileno;
                          $action->smsmsg = $package_expiry2;
                          $action->smsrequestid = $otpsend;
                          $action->subject = 'Package Expiry Message By System Before 3 days';
                          $action->save();
                      }

                        // $package_expiry_response = Curl::to('http://sms.weybee.in/api/sendapi.php?auth_key=2169KrEMnx2ZgAqSfavSSC&mobiles='.$mobileno.'&message='.$package_expiry.'&sender=PYOFIT&route=4')->get();

                        //             $action = new Notificationmsgdetails();
                        //             $action->user_id = session()->get('admin_id');
                        //             $action->mobileno = $mp->mmobileno;
                        //             $action->smsmsg = $package_expiry;
                        //             $action->smsrequestid = $package_expiry_response;
                        //             $action->subject = 'Package Expiry Message By System befor 3 days';
                        //             $action->save();
                    }
                    if ($interval == 2) {
                       echo $mp->mobileno;
                       // $expirymsg = "Your Package Is Expire  After 2 days";
                            // echo "<br/>";

                          // $package_expiry= str_replace("[ExpiryMsg]",$expirymsg,$package_expiry);
                            // $package_expiry2 = $package_expiry;
                            $package_expiry = urlencode($package_expiry_msg);

                         $smssetting = Smssetting::where('status',1)->where('smsonoff','Active')->first();
                         if ($smssetting) {
                         $u = $smssetting->url;
                         $url= str_replace('$mobileno', $mobileno, $u);
                         $url=str_replace('$msg', $package_expiry, $url);
                        // echo  $url;exit;
                          

                          $otpsend = Curl::to($url)->get();

                          $action = new Notificationmsgdetails();
                          $action->user_id = session()->get('admin_id');
                          $action->mobileno = $mobileno;
                          $action->smsmsg = $package_expiry2;
                          $action->smsrequestid = $otpsend;
                          $action->subject = 'Package Expiry Message By System Before 3 days';
                          $action->save();
                      }

                        // $package_expiry_response = Curl::to('http://sms.weybee.in/api/sendapi.php?auth_key=2169KrEMnx2ZgAqSfavSSC&mobiles='.$mobileno.'&message='.$package_expiry.'&sender=PYOFIT&route=4')->get();

                        //             $action = new Notificationmsgdetails();
                        //             $action->user_id = session()->get('admin_id');
                        //             $action->mobileno = $mp->mmobileno;
                        //             $action->smsmsg = $package_expiry;
                        //             $action->smsrequestid = $package_expiry_response;
                        //             $action->subject = 'Package Expiry Message By System After 2 days';
                        //             $action->save();
                    }
                    // echo 'tgdfghd'.$interval;
                    if ($interval == 1) {
                       echo $mp->mobileno;
                       // $expirymsg = "Your Package Is Expire  Tomorrow";
                            // echo "<br/>";

                         // $package_expiry= str_replace("[ExpiryMsg]",$expirymsg,$package_expiry);
                            // $package_expiry2 = $package_expiry;
                            $package_expiry = urlencode($package_expiry_msg);

                         $smssetting = Smssetting::where('status',1)->where('smsonoff','Active')->first();
                         if ($smssetting) {
                         $u = $smssetting->url;
                         $url= str_replace('$mobileno', $mobileno, $u);
                         $url=str_replace('$msg', $package_expiry, $url);
                        // echo  $url;exit;
                          

                          $otpsend = Curl::to($url)->get();

                          $action = new Notificationmsgdetails();
                          $action->user_id = session()->get('admin_id');
                          $action->mobileno = $mobileno;
                          $action->smsmsg = $package_expiry2;
                          $action->smsrequestid = $otpsend;
                          $action->subject = 'Package Expiry Message By System Befor 1 days';
                          $action->save();
                      }

                    }
                      if ($interval == 0) {
                            echo $mp->mobileno;
                            // $expirymsg = "Your package is expiried";

                            // $package_expiry= str_replace("[ExpiryMsg]",$expirymsg,$package_expiry);
                            // $package_expiry2 = $package_expiry;
                            $package_expiry = urlencode($package_expiry_msg);

                         $smssetting = Smssetting::where('status',1)->where('smsonoff','Active')->first();
                         if ($smssetting) {
                         $u = $smssetting->url;
                         $url= str_replace('$mobileno', $mobileno, $u);
                         $url=str_replace('$msg', $package_expiry, $url);
                        // echo  $url;exit;
                          

                          $otpsend = Curl::to($url)->get();

                          $action = new Notificationmsgdetails();
                          $action->user_id = session()->get('admin_id');
                          $action->mobileno = $mobileno;
                          $action->smsmsg = $package_expiry2;
                          $action->smsrequestid = $otpsend;
                          $action->subject = 'Package Expiry Message By System On The Day';
                          $action->save();
                            
                            }

                         // $package_expiry_response = Curl::to('http://sms.weybee.in/api/sendapi.php?auth_key=2169KrEMnx2ZgAqSfavSSC&mobiles='.$mobileno.'&message='.$package_expiry.'&sender=PYOFIT&route=4')->get();

                         //            $action = new Notificationmsgdetails();
                         //            $action->user_id = session()->get('admin_id');
                         //            $action->mobileno = $mp->mmobileno;
                         //            $action->smsmsg = $package_expiry;
                         //            $action->smsrequestid = $package_expiry_response;
                         //            $action->subject = 'Package Expiry Message By System On The Day';
                         //            $action->save();
                             
                      
                                
                   }

                }


            if ($dndmpemail) {
                    # code...
                
                if ($mp->email == 1) {

                    // $expirymsg = "Your Package Is Expire  After 15 days";
                            // echo "<br/>";
                      
                        if ($interval == 15 ) {
                        echo $dndmpemail;

                            // $package_expiry= str_replace("[ExpiryMsg]",$expirymsg,$package_expiry);
                             // echo $package_expiry;
                            // $package_expiry2 = $package_expiry;
                            $package_expiry = urlencode($package_expiry_msg);

                                 $data = [
                                                 //'data' => 'Rohit',
                                                 'msg' => $package_expiry2,
                                                 'mail'=> $dndmpemail,
                                                 'subject' => $emailsetting->hearder,
                                                 'senderemail'=> $emailsetting->senderemailid,
                                              ];

                                         // echo $data;

                                        Mail::send('admin.name', ["data1"=>$data], function($message) use ($data){

                                              $message->from($data['senderemail'], 'Test');
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
                                        $action->messagefor = 'Package Expiry Mail By System Before 15 Days';
                                        $action->save();
                                     

                                }


                
                        if ($interval == 7) {

                             // $expirymsg = "Your Package Is Expire  After 7 days";
                            // echo "<br/>";

                            // $package_expiry= str_replace("[ExpiryMsg]",$expirymsg,$package_expiry);
                             // echo $package_expiry;
                            // $package_expiry2 = $package_expiry;
                            // $package_expiry = urlencode($package_expiry_msg);
                       
                        $data = [
                                                 //'data' => 'Rohit',
                                                 'msg' => $package_expiry2,
                                                 'mail'=> $dndmpemail,
                                                 'subject' => $emailsetting->hearder,
                                                 'senderemail'=> $emailsetting->senderemailid,
                                              ];

                                        // print_r($data);

                                        Mail::send('admin.name', ["data1"=>$data], function($message) use ($data){

                                              $message->from($data['senderemail'], 'Test');
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
                                        $action->messagefor = 'Package Expiry Mail By System Before 7 Days';
                                        $action->save();
                    }
                    if ($interval == 3) {

                        // $expirymsg = "Your Package Is Expire  After 3 days";
                            // echo "<br/>";

                            // $package_expiry= str_replace("[ExpiryMsg]",$expirymsg,$package_expiry);
                             // echo $package_expiry;
                            // $package_expiry2 = $package_expiry;
                            $package_expiry = urlencode($package_expiry_msg);
                       
                        $data = [
                                                 //'data' => 'Rohit',
                                                 'msg' => $package_expiry2,
                                                 'mail'=> $dndmpemail,
                                                 'subject' => $emailsetting->hearder,
                                                 'senderemail'=> $emailsetting->senderemailid,
                                              ];

                                        // print_r($data);

                                        Mail::send('admin.name', ["data1"=>$data], function($message) use ($data){

                                              $message->from($data['senderemail'], 'Test');
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
                                        $action->messagefor = 'Package Expiry Mail By System Before 3 Days';
                                        $action->save();
                    }
                    if ($interval == 2) {

                        // $expirymsg = "Your Package Is Expire  After 2 days";
                            // echo "<br/>";

                            // $package_expiry= str_replace("[ExpiryMsg]",$expirymsg,$package_expiry);
                             // echo $package_expiry;
                            // $package_expiry2 = $package_expiry;
                            $package_expiry = urlencode($package_expiry);
                       
                        $data = [
                                                 //'data' => 'Rohit',
                                                 'msg' => $package_expiry2,
                                                 'mail'=> $dndmpemail,
                                                 'subject' => $emailsetting->hearder,
                                                 'senderemail'=> $emailsetting->senderemailid,
                                              ];

                                        // print_r($data);

                                        Mail::send('admin.name', ["data1"=>$data], function($message) use ($data){

                                              $message->from($data['senderemail'], 'Test');
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
                                        $action->messagefor = 'Package Expiry Mail By System Before 2 Days';
                                        $action->save();
                    }
                    // echo 'tgdfghd'.$interval;
                    if ($interval == 1) {

                        // $expirymsg = "Your Package Is Expire  Tomorrow";
                            // echo "<br/>";

                            // $package_expiry= str_replace("[ExpiryMsg]",$expirymsg,$package_expiry);
                             // echo $package_expiry;
                            // $package_expiry2 = $package_expiry;
                            $package_expiry = urlencode($package_expiry_msg);
                       
                        $data = [
                                                 //'data' => 'Rohit',
                                                 'msg' => $package_expiry2,
                                                 'mail'=> $dndmpemail,
                                                 'subject' => $emailsetting->hearder,
                                                 'senderemail'=> $emailsetting->senderemailid,
                                              ];

                                        // print_r($data);

                                        Mail::send('admin.name', ["data1"=>$data], function($message) use ($data){

                                              $message->from($data['senderemail'], 'Test');
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
                                        $action->messagefor = 'Package Expiry Mail By System Before 1 Days';
                                        $action->save();
                    }
                      if ($interval == 0) {

                        // $expirymsg = "Your Package Is Expiried";
                            // echo "<br/>";

                            // $package_expiry= str_replace("[ExpiryMsg]",$expirymsg,$package_expiry);
                             // echo $package_expiry;
                            // $package_expiry2 = $package_expiry;
                            $package_expiry = urlencode($package_expiry_msg);


                                $data = [
                                                 //'data' => 'Rohit',
                                                 'msg' => $package_expiry2,
                                                 'mail'=> $dndmpemail,
                                                 'subject' => $emailsetting->hearder,
                                                 'senderemail'=> $emailsetting->senderemailid,
                                              ];

                                        // print_r($data);

                                        Mail::send('admin.name', ["data1"=>$data], function($message) use ($data){

                                              $message->from($data['senderemail'], 'Test');
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
                                        $action->messagefor = 'Package Expiry Mail By System On That Day';
                                        $action->save();
                             
                      
                                
                   }

                }
                    
               } 
                
            }

 // DB::enableQueryLog();
                            


                $due = Payment::leftjoin('member','member.userid','=','payments.userid')
                        ->leftjoin('notification','notification.mobileno','=','member.mobileno')
                        ->select(DB::raw('MAX(paymentid) as pid'))
                        ->groupBy('payments.userid')
                        ->get()
                        ->all();

                $pid=array();

                for($i=0;$i<count($due);$i++)
                {
                    $pid[]=$due[$i]['pid'];
                }
                // $pids= implode(',',$pid);
                // dd($pids);
                $due = Payment::leftjoin('member','member.userid','=','payments.userid')
                        ->leftjoin('notification','notification.mobileno','=','member.mobileno')
                        ->leftjoin('memberpackages','member.userid','=','memberpackages.userid')
                        ->leftjoin('schemes','memberpackages.schemeid','=','schemes.schemeid')
                        ->select('payments.*','member.email as memail','member.*','notification.*','schemes.*','memberpackages.*')
                        ->whereIn('payments.paymentid',$pid)
                        ->whereDate('payments.duedate', '>=', $todays)
                        ->get()
                        ->all();



                   // dd($due);
                // dd($users);

     // print_r( DB::getQueryLog());


            foreach ($due as $u) {

                        

                         $datetime1 = date_create($todays); 
                         $datetime2 = date_create($u->duedate); 
                         $interval = date_diff($datetime1, $datetime2);
                         $interval = $interval->format('%R%a days');
                          // print_r($interval);

                         $fname = $u->firstname;
                         $lname = $u->lastname;
                         $mobileno = $u->mobileno;
                         $date = $u->duedate;
                         $dnd  = $u->sms;
                         $dndemail = $u->email;
                         $membermail = $u->memail;
                         $amount = $u->remainingamount;
                         $packagename = $u->schemename;
                         $pkgassigndate = $u->joindate;


                         // print_r($amount);echo "<br/>";
                         // print_r($u->firstname);echo "<br/>";


                            // $duedate_msg = str_replace(array('[FirstName]','[LastName]'),array($fname, $lname),$duedate_msg);


                        // $msg= DB::table('messages')->where('messagesid','18')->get()->first();
                        // $duedate_msg =$msg->message;
                        $duedate_msg = str_replace("[FirstName]",$fname,$duedate_msg);
                        $duedate_msg= str_replace("[LastName]",$lname,$duedate_msg);
                        $duedate_msg= str_replace("[packgename]",$packagename,$duedate_msg);
                        $duedate_msg= str_replace("[date_of_packge_assign]",$pkgassigndate,$duedate_msg);
                        $duedate_msg= str_replace("[amount]",$amount,$duedate_msg);
                        $duedate_msg= str_replace("[date]",$date,$duedate_msg);
                        $duedate_msg2 = $duedate_msg;


                          // dd($duedate_msg);echo "<br/>";echo "<br/>";


                             if ($u->sms == 1) {
                              
                                if ($interval == 15 ) {

                                    // $expirymsg = "15 days ago DueDate Message";
                                        // echo "<br/>";

                                    // $duedate_msg= str_replace("[ExpiryMsg]",$expirymsg,$duedate_msg);
                                    
                                    $duedate_msg = urlencode($duedate_msg);

                                    $smssetting = Smssetting::where('status',1)->where('smsonoff','Active')->first();
                                    if ($smssetting) {
                                    $u = $smssetting->url;
                                    $url= str_replace('$mobileno', $mobileno, $u);
                                    $url=str_replace('$msg', $duedate_msg, $url);


                                    
                                    // echo  $fname.''.$lname.'';
                                  // echo  $msg;exit;

                                   
                                  $otpsend = Curl::to($url)->get();

                                  $action = new Notificationmsgdetails();
                                  $action->user_id = session()->get('admin_id');
                                  $action->mobileno = $mobileno;
                                  $action->smsmsg = $duedate_msg2;
                                  $action->smsrequestid = $otpsend;
                                  $action->subject = 'DueDate Message By System Befor 15 days';
                                  $action->save(); 

                                  }                
                   
                                   

                                    // $duedate_msg_response = Curl::to('http://sms.weybee.in/api/sendapi.php?auth_key=2169KrEMnx2ZgAqSfavSSC&mobiles='.$mobileno.'&message='.$duedate_msg.'&sender=PYOFIT&route=4')->get();

                                    // $action = new Notificationmsgdetails();
                                    // $action->user_id = session()->get('admin_id');
                                    // $action->mobileno = $u->mobileno;
                                    // $action->smsmsg = $duedate_msg;
                                    // $action->smsrequestid = $duedate_msg_response;
                                    // $action->subject = 'DueDate Message By System befor 15 days';
                                    // $action->save();

                                }
                                if ($interval == 7) {

                                    // $expirymsg = "7 days ago DueDate Message";
                                        // echo "<br/>";
                                   
                                    // $duedate_msg= str_replace("[ExpiryMsg]",$expirymsg,$duedate_msg);
                                    // $duedate_msg2 = $duedate_msg;
                                    $duedate_msg = urlencode($duedate_msg);

                                    $smssetting = Smssetting::where('status',1)->where('smsonoff','Active')->first();
                                    if ($smssetting) {
                                    $u = $smssetting->url;
                                    $url= str_replace('$mobileno', $mobileno, $u);
                                    $url=str_replace('$msg', $duedate_msg, $url);

                                    $otpsend = Curl::to($url)->get();

                                  $action = new Notificationmsgdetails();
                                  $action->user_id = session()->get('admin_id');
                                  $action->mobileno = $mobileno;
                                  $action->smsmsg = $duedate_msg2;
                                  $action->smsrequestid = $otpsend;
                                  $action->subject = 'DueDate Message By System befor 7 days';
                                  $action->save();

                                }

                                    // $action = new Notificationmsgdetails();
                                    // $action->user_id = session()->get('admin_id');
                                    // $action->mobileno = $u->mobileno;
                                    // $action->smsmsg = $duedate_msg;
                                    // $action->smsrequestid = $duedate_msg_response;
                                    // $action->subject = 'DueDate Message By System befor 7 days';
                                    // $action->save();
                                }
                                if ($interval == 3) {
                                   
                                   // $expirymsg = "3 days ago DueDate Message";
                                        // echo "<br/>";

                                    // $duedate_msg= str_replace("[ExpiryMsg]",$expirymsg,$duedate_msg);
                                    // $duedate_msg2 = $duedate_msg;
                                    $duedate_msg = urlencode($duedate_msg);

                                    $smssetting = Smssetting::where('status',1)->where('smsonoff','Active')->first();
                                    if ($smssetting) {
                                    $u = $smssetting->url;
                                    $url= str_replace('$mobileno', $mobileno, $u);
                                    $url=str_replace('$msg', $duedate_msg, $url);

                                    $otpsend = Curl::to($url)->get();

                                    $action = new Notificationmsgdetails();
                                    $action->user_id = session()->get('admin_id');
                                    $action->mobileno = $mobileno;
                                    $action->smsmsg = $duedate_msg2;
                                    $action->smsrequestid = $otpsend;
                                    $action->subject = 'DueDate Message By System befor 3 days';
                                    $action->save();
                                }

                                    // $duedate_msg_response = Curl::to('http://sms.weybee.in/api/sendapi.php?auth_key=2169KrEMnx2ZgAqSfavSSC&mobiles='.$mobileno.'&message='.$duedate_msg.'&sender=PYOFIT&route=4')->get();

                                    // $action = new Notificationmsgdetails();
                                    // $action->user_id = session()->get('admin_id');
                                    // $action->mobileno = $u->mobileno;
                                    // $action->smsmsg = $duedate_msg;
                                    // $action->smsrequestid = $duedate_msg_response;
                                    // $action->subject = 'DueDate Message By System befor 3 days';
                                    // $action->save();
                                }
                                if ($interval == 2) {
                                   
                                   // $expirymsg = "2 days ago DueDate Message";
                                        // echo "<br/>";

                                    // $duedate_msg= str_replace("[ExpiryMsg]",$expirymsg,$duedate_msg);
                                    // $duedate_msg2 = $duedate_msg;
                                    $duedate_msg = urlencode($duedate_msg);

                                    $smssetting = Smssetting::where('status',1)->where('smsonoff','Active')->first();
                                    if ($smssetting) {
                                    $u = $smssetting->url;
                                    $url= str_replace('$mobileno', $mobileno, $u);
                                    $url=str_replace('$msg', $duedate_msg, $url);

                                    $otpsend = Curl::to($url)->get();

                                    $action = new Notificationmsgdetails();
                                    $action->user_id = session()->get('admin_id');
                                    $action->mobileno = $mobileno;
                                    $action->smsmsg = $duedate_msg2;
                                    $action->smsrequestid = $otpsend;
                                    $action->subject = 'DueDate Message By System befor 2 days';
                                    $action->save();
                                }
                                    // $duedate_msg_response = Curl::to('http://sms.weybee.in/api/sendapi.php?auth_key=2169KrEMnx2ZgAqSfavSSC&mobiles='.$mobileno.'&message='.$duedate_msg.'&sender=PYOFIT&route=4')->get();

                                    // $action = new Notificationmsgdetails();
                                    // $action->user_id = session()->get('admin_id');
                                    // $action->mobileno = $u->mobileno;
                                    // $action->smsmsg = $duedate_msg;
                                    // $action->smsrequestid = $duedate_msg_response;
                                    // $action->subject = 'DueDate Message By System befor 2 days';
                                    // $action->save();
                                }
                                
                                if ($interval == 1) {
                                   
                                   // $expirymsg = "1 days ago DueDate Message";
                                        // echo "<br/>";

                                    // $duedate_msg= str_replace("[ExpiryMsg]",$expirymsg,$duedate_msg);
                                    // $duedate_msg2 = $duedate_msg;
                                    $duedate_msg = urlencode($duedate_msg);

                                    $smssetting = Smssetting::where('status',1)->where('smsonoff','Active')->first();
                                    if ($smssetting) {
                                    $u = $smssetting->url;
                                    $url= str_replace('$mobileno', $mobileno, $u);
                                    $url=str_replace('$msg', $duedate_msg, $url);

                                    $otpsend = Curl::to($url)->get();

                                    $action = new Notificationmsgdetails();
                                    $action->user_id = session()->get('admin_id');
                                    $action->mobileno = $mobileno;
                                    $action->smsmsg = $duedate_msg2;
                                    $action->smsrequestid = $otpsend;
                                    $action->subject = 'DueDate Message By System befor 1 days';
                                    $action->save();
                                }

                                    // $duedate_msg_response = Curl::to('http://sms.weybee.in/api/sendapi.php?auth_key=2169KrEMnx2ZgAqSfavSSC&mobiles='.$mobileno.'&message='.$duedate_msg.'&sender=PYOFIT&route=4')->get();

                                    // $action = new Notificationmsgdetails();
                                    // $action->user_id = session()->get('admin_id');
                                    // $action->mobileno = $u->mobileno;
                                    // $action->smsmsg = $duedate_msg;
                                    // $action->smsrequestid = $duedate_msg_response;
                                    // $action->subject = 'DueDate Message By System befor 1 days';
                                    // $action->save();
                                }
                                 
                                if (Carbon::now()->toDateString() == $date) {
                                      
                                     // echo $u->mobileno."Last Day For Pay Your Payment";
                                     // echo "<br/>";

                                    // $expirymsg = "Last Day For Pay Your Payment";
                                        // echo "<br/>";

                                    // $duedate_msg= str_replace("[ExpiryMsg]",$expirymsg,$duedate_msg);
                                    // $duedate_msg2 = $duedate_msg;
                                    $duedate_msg = urlencode($duedate_msg);

                                    $smssetting = Smssetting::where('status',1)->where('smsonoff','Active')->first();
                                    if ($smssetting) {
                                    $u = $smssetting->url;
                                    $url= str_replace('$mobileno', $mobileno, $u);
                                    $url=str_replace('$msg', $duedate_msg, $url);

                                    $otpsend = Curl::to($url)->get();

                                    $action = new Notificationmsgdetails();
                                    $action->user_id = session()->get('admin_id');
                                    $action->mobileno = $mobileno;
                                    $action->smsmsg = $duedate_msg2;
                                    $action->smsrequestid = $otpsend;
                                    $action->subject = 'DueDate Message By System on Tha Day';
                                    $action->save();
                                }

                                     // $duedate_msg_response = Curl::to('http://sms.weybee.in/api/sendapi.php?auth_key=2169KrEMnx2ZgAqSfavSSC&mobiles='.$mobileno.'&message='.$duedate_msg.'&sender=PYOFIT&route=4')->get();

                                    // $action = new Notificationmsgdetails();
                                    // $action->user_id = session()->get('admin_id');
                                    // $action->mobileno = $u->mobileno;
                                    // $action->smsmsg = $duedate_msg;
                                    // $action->smsrequestid = $duedate_msg_response;
                                    // $action->subject = 'DueDate Message By System on Tha Day';
                                    // $action->save();
                                   }
                           
                             }

                    if ($dndemail) {
                              

                         if ($dndemail == 1) {
                              
                                if ($interval == 15 ) {
                   
                                   // $expirymsg = "15 days ago DueDate Message";
                                        // echo "<br/>";

                                    // $duedate_msg= str_replace("[ExpiryMsg]",$expirymsg,$duedate_msg);
                                    // $duedate_msg2 = $duedate_msg;
                                    $duedate_msg = urlencode($duedate_msg);

                                     $data = [
                                                 //'data' => 'Rohit',
                                                 'msg' => $duedate_msg2,
                                                 'mail'=> $membermail,
                                                 'subject' => $emailsetting->hearder,
                                                 'senderemail'=> $emailsetting->senderemailid,
                                              ];

                                        // print_r($data);

                                        Mail::send('admin.name', ["data1"=>$data], function($message) use ($data){

                                              $message->from($data['senderemail'], 'Test');
                                              $message->to($data['mail']);
                                              $message->subject($data['subject']);

                                        });

                                        $action = new Emailnotificationdetails();
                                        $action->user_id = session()->get('admin_id');
                                        $action->mobileno = $mobileno;
                                        $action->message = $duedate_msg2;
                                        $action->emailform = $data['senderemail'];
                                        $action->emailto = $data['mail'];
                                        $action->subject = $data['subject'];
                                        $action->messagefor = 'DueDate Expiry Mail By System Before 15 Days';
                                        $action->save();

                                }
                                if ($interval == 7) {
                                   
                                   //echo $membermail."7 day go to expiry package";
                                    // echo "<br/>";
                                    // $expirymsg = "7 days ago DueDate Message";
                                        // echo "<br/>";

                                    // $duedate_msg= str_replace("[ExpiryMsg]",$expirymsg,$duedate_msg);
                                    // $duedate_msg2 = $duedate_msg;
                                    $duedate_msg = urlencode($duedate_msg);

                                    $data = [
                                                 //'data' => 'Rohit',
                                                 'msg' => $duedate_msg2,
                                                 'mail'=> $membermail,
                                                 'subject' => $emailsetting->hearder,
                                                 'senderemail'=> $emailsetting->senderemailid,
                                              ];

                                        // print_r($data);

                                        Mail::send('admin.name', ["data1"=>$data], function($message) use ($data){

                                              $message->from($data['senderemail'], 'Test');
                                              $message->to($data['mail']);
                                              $message->subject($data['subject']);

                                        });

                                        $action = new Emailnotificationdetails();
                                        $action->user_id = session()->get('admin_id');
                                        $action->mobileno = $mobileno;
                                        $action->message = $duedate_msg2;
                                        $action->emailform = $data['senderemail'];
                                        $action->emailto = $data['mail'];
                                        $action->subject = $data['subject'];
                                        $action->messagefor = 'DueDate Expiry Mail By System Before 7 Days';
                                        $action->save();
                                }
                                if ($interval == 3) {
                                   
                                   //echo $membermail."3 day go to expiry package";
                                    // echo "<br/>";

                                    // $expirymsg = "3 days ago DueDate Message";
                                        // echo "<br/>";

                                    // $duedate_msg= str_replace("[ExpiryMsg]",$expirymsg,$duedate_msg);
                                    // $duedate_msg2 = $duedate_msg;
                                    $duedate_msg = urlencode($duedate_msg);

                                    $data = [
                                                 //'data' => 'Rohit',
                                                 'msg' => $duedate_msg2,
                                                 'mail'=> $membermail,
                                                 'subject' => $emailsetting->hearder,
                                                 'senderemail'=> $emailsetting->senderemailid,
                                              ];

                                        // print_r($data);

                                        Mail::send('admin.name', ["data1"=>$data], function($message) use ($data){

                                              $message->from($data['senderemail'], 'Test');
                                              $message->to($data['mail']);
                                              $message->subject($data['subject']);

                                        });

                                        $action = new Emailnotificationdetails();
                                        $action->user_id = session()->get('admin_id');
                                        $action->mobileno = $mobileno;
                                        $action->message = $duedate_msg2;
                                        $action->emailform = $data['senderemail'];
                                        $action->emailto = $data['mail'];
                                        $action->subject = $data['subject'];
                                        $action->messagefor = 'DueDate Expiry Mail By System Before 3 Days';
                                        $action->save();
                                }
                                if ($interval == 2) {
                                   
                                   //echo $membermail."2 day go to expiry package";
                                    // echo "<br/>";

                                    // $expirymsg = "2 days ago DueDate Message";
                                        // echo "<br/>";

                                    // $duedate_msg= str_replace("[ExpiryMsg]",$expirymsg,$duedate_msg);
                                    // $duedate_msg2 = $duedate_msg;
                                    $duedate_msg = urlencode($duedate_msg);

                                   $data = [
                                                 //'data' => 'Rohit',
                                                 'msg' => $duedate_msg2,
                                                 'mail'=> $membermail,
                                                 'subject' => $emailsetting->hearder,
                                                 'senderemail'=> $emailsetting->senderemailid,
                                              ];

                                        // print_r($data);

                                        Mail::send('admin.name', ["data1"=>$data], function($message) use ($data){

                                              $message->from($data['senderemail'], 'Test');
                                              $message->to($data['mail']);
                                              $message->subject($data['subject']);

                                        });

                                        $action = new Emailnotificationdetails();
                                        $action->user_id = session()->get('admin_id');
                                        $action->mobileno = $mobileno;
                                        $action->message = $duedate_msg2;
                                        $action->emailform = $data['senderemail'];
                                        $action->emailto = $data['mail'];
                                        $action->subject = $data['subject'];
                                        $action->messagefor = 'DueDate Expiry Mail By System Before 2 Days';
                                        $action->save();
                                }
                                
                                if ($interval == 1) {
                                   
                                   //echo $membermail."Your  package expiry tomorrow";
                                    // echo "<br/>";

                                    // $expirymsg = "1 days ago DueDate Message";
                                        // echo "<br/>";

                                    // $duedate_msg= str_replace("[ExpiryMsg]",$expirymsg,$duedate_msg);
                                    // $duedate_msg2 = $duedate_msg;
                                    $duedate_msg = urlencode($duedate_msg);

                                    $data = [
                                                 //'data' => 'Rohit',
                                                 'msg' => $duedate_msg2,
                                                 'mail'=> $membermail,
                                                 'subject' => $emailsetting->hearder,
                                                 'senderemail'=> $emailsetting->senderemailid,
                                              ];

                                        // print_r($data);

                                        Mail::send('admin.name', ["data1"=>$data], function($message) use ($data){

                                              $message->from($data['senderemail'], 'Test');
                                              $message->to($data['mail']);
                                              $message->subject($data['subject']);

                                        });

                                        $action = new Emailnotificationdetails();
                                        $action->user_id = session()->get('admin_id');
                                        $action->mobileno = $mobileno;
                                        $action->message = $duedate_msg2;
                                        $action->emailform = $data['senderemail'];
                                        $action->emailto = $data['mail'];
                                        $action->subject = $data['subject'];
                                        $action->messagefor = 'DueDate Expiry Mail By System Before 1 Days';
                                        $action->save();
                                }
                                 
                                   if (Carbon::now()->toDateString() == $date) {
                                      
                                     //echo $membermail."Your package is expiried";
                                     // echo "<br/>";

                                     // $expirymsg = "15 days ago DueDate Message";
                                        // echo "<br/>";

                                    // $duedate_msg= str_replace("[ExpiryMsg]",$expirymsg,$duedate_msg);
                                    // $duedate_msg2 = $duedate_msg;
                                    $duedate_msg = urlencode($duedate_msg);

                                    $data = [
                                                 //'data' => 'Rohit',
                                                 'msg' => $duedate_msg2,
                                                 'mail'=> $membermail,
                                                 'subject' => $emailsetting->hearder,
                                                 'senderemail'=> $emailsetting->senderemailid,
                                              ];

                                        // print_r($data);

                                        Mail::send('admin.name', ["data1"=>$data], function($message) use ($data){

                                              $message->from($data['senderemail'], 'Test');
                                              $message->to($data['mail']);
                                              $message->subject($data['subject']);

                                        });

                                        $action = new Emailnotificationdetails();
                                        $action->user_id = session()->get('admin_id');
                                        $action->mobileno = $mobileno;
                                        $action->message = $duedate_msg2;
                                        $action->emailform = $data['senderemail'];
                                        $action->emailto = $data['mail'];
                                        $action->subject = $data['subject'];
                                        $action->messagefor = 'DueDate Expiry Mail By System On That Date';
                                        $action->save();
                                   }
                           
                             }

                                # code...
                             }

            }

            
           


    $member_birthday =Member::leftjoin('notification','notification.mobileno','=','member.mobileno')
                        ->where('member.status',1)
                        ->select('member.status','member.anniversary','member.birthday','notification.sms','notification.email','member.email as memail','member.firstname','member.lastname','member.mobileno')
                        ->whereDay('birthday','=',$today->format('d'))
                        ->whereMonth('birthday','=',$today->format('m'))
                        ->get()
                        ->all();

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

                     $fname = $mb->firstname;
                     $lname = $mb->lastname;
                     $mobileno = $mb->mobileno;
                     $bemail = $mb->memail;

                   
                    $bithday_msg_new =$msg->message;
                    $bithday_msg_new = str_replace("[FirstName]",$fname,$bithday_msg_new);
                    $bithday_msg_new= str_replace("[LastName]",$lname,$bithday_msg_new);
                    $bithday_msg_new2 = $bithday_msg_new;
                    $bithday_msg_new = urlencode($bithday_msg_new);

                    // print_r($bithday_msg_new2);echo "<br/>";
                            
                      //$bithday_msg_new = str_replace("[FirstName]",$fname,$bithday_msg);
                      //$bithday_msg_new2 = str_replace("[LastName]",$lname,$bithday_msg_new);
                     // $bithday_msg_new = str_replace(array('[FirstName]', '[LastName]'),array($fname, $lname),$bithday_msg);
                       // print_r($bithday_msg_new);echo "<br/>";

                    // $mbirthday = $mb->birthday;
                    // $ma = $mb->anniversary;

                    if ($mb->sms == 1) {

                        // $birthdaymsg = "Bithday Massage";
                        //                 echo "<br/>";

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

                       // $birthday = Curl::to('http://sms.weybee.in/api/sendapi.php?auth_key=2169KrEMnx2ZgAqSfavSSC&mobiles='.$mobileno.'&message='.$bithday_msg_new.'&sender=PYOFIT&route=4')->get();

                        // $action = new Notificationmsgdetails();
                        // $action->user_id = session()->get('admin_id');
                        // $action->mobileno = $mobileno;
                        // $action->smsmsg = $bithday_msg_new;
                        // $action->smsrequestid = $birthday;
                        // $action->subject = 'Birthday Message By System';
                        // $action->save();
                    }

                    // print_r($mb->memail);echo "<br/>";

                    if ($mb->email == 1) {

                        $data = [
                                 //'data' => 'Rohit',
                                 'msg' => $bithday_msg_new2,
                                 'mail'=> $bemail,
                                 'subject' => $emailsetting->hearder,
                                 'senderemail'=> $emailsetting->senderemailid,
                              ];



                        Mail::send('admin.name', ["data1"=>$data], function($message) use ($data){

                              $message->from($data['senderemail'], 'Test');
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

                    // $datetime1 = date_create($todays); 
                    // $datetime2 = date_create($mbirthday); 
                    // $anniversary = date_create($ma);

        

                            // if ($datetime1->format('m-d') === $datetime2->format('m-d') ) {

                            //     $dnd_birth = Member::select('memberid', 'birthday',DB::raw('MAX(memberid) as bid'))->groupBy('memberid')->get();


                            //     // foreach ($dnd_birth as $db) {
                            //     //     print_r($db->memberid);
                            //     //   print_r($db->firstname);
                            //     // }

                              
                            //        // if ($ndnd) {
                            //        //  if ($ndnd->sms == 1) {

                            //        //      //$birthday = Curl::to('http://sms.weybee.in/api/sendapi.php?auth_key=2169KrEMnx2ZgAqSfavSSC&mobiles='.$mobileno.'&message='.$bithday_msg.'&sender=PYOFIT&route=4')->get();
                            //        //      print_r($bithday_msg);
                            //        //      }
                            //        //  }
                                
                                
                                 
                            // }
                 // if ($datetime1->format('m-d') === $anniversary->format('m-d')) {
                 //                //print_r($ma);
                 //                //echo "send SMS";echo "<br/>";
                 //            }

            }
               
        }
            
    $msg= DB::table('messages')->where('messagesid','4')->get()->first();

            if ($member_anniversary) {

                foreach ($member_anniversary  as  $ma) {

                     $fname = $ma->firstname;
                     $lname = $ma->lastname;
                     $mobileno = $ma->mobileno;
                     $aemail = $ma->memail;

                    
                    $anniversary_msg_new =$msg->message;
                    $anniversary_msg_new = str_replace("[FirstName]",$fname,$anniversary_msg_new);
                    $anniversary_msg_new= str_replace("[LastName]",$lname,$anniversary_msg_new);
                    $anniversary_msg_new2 = $anniversary_msg_new;
                    $anniversary_msg_new = urlencode($anniversary_msg_new);

                    print_r($anniversary_msg_new2);echo "<br/>";
                            
                      //$bithday_msg_new = str_replace("[FirstName]",$fname,$bithday_msg);
                      //$bithday_msg_new2 = str_replace("[LastName]",$lname,$bithday_msg_new);
                     // $anniversary_msg_new = str_replace(array('[FirstName]','[LastName]'),array($fname, $lname),$anniversary_msg);

                    // $anniversary_encode =  urlencode($anniversary_msg_new);

                    if ($ma->sms == 1) {

                        // print_r($mobileno);

                        $smssetting = Smssetting::where('status',1)->where('smsonoff','Active')->first();
                        if ($smssetting) {
                        $u = $smssetting->url;
                        $url= str_replace('$mobileno', $mobileno, $u);
                        $url=str_replace('$msg', $anniversary_msg_new, $url);

                        $otpsend = Curl::to($url)->get();

                        $action = new Notificationmsgdetails();
                        $action->user_id = session()->get('admin_id');
                        $action->mobileno = $mobileno;
                        $action->smsmsg = $anniversary_msg_new2;
                        $action->smsrequestid = $otpsend;
                        $action->subject = 'Anniversary Message By System';
                        $action->save();

                    }
                        // $anniversary = Curl::to('http://sms.weybee.in/api/sendapi.php?auth_key=2169KrEMnx2ZgAqSfavSSC&mobiles='.$mobileno.'&message='.$anniversary_encode.'&sender=PYOFIT&route=4')->get();

                        //$url = 'http://sms.weybee.in/api/sendapi.php?auth_key=2169KrEMnx2ZgAqSfavSSC&mobiles='.$mobileno.'&message='.$anniversary_msg.'&sender=PYOFIT&route=4'; 
                        // $url = str_replace(" ", '%20', $url);

                        // $ch = curl_init();
                        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        // curl_setopt($ch, CURLOPT_URL, $url);
                         //echo $ret = curl_exec($anniversary);
                                              

                        // print_r($anniversary);echo "<br/>";

                        // $action = new Notificationmsgdetails();
                        // $action->user_id = session()->get('admin_id');
                        // $action->mobileno = $mobileno;
                        // $action->smsmsg = $anniversary_msg_new2;
                        // $action->smsrequestid = $anniversary;
                        // $action->subject = 'Anniversary Message By System';
                        // $action->save();
                    }

                    if ($ma->email == 1) {

                        $data = [
                                 //'data' => 'Rohit',
                                 'msg' => $anniversary_msg_new,
                                 'mail'=> $aemail,
                                 'subject' => $emailsetting->hearder,
                                 'senderemail'=> $emailsetting->senderemailid,
                              ];

                        // print_r($data);

                        Mail::send('admin.name', ["data1"=>$data], function($message) use ($data){

                              $message->from($data['senderemail'], 'Test');
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
    
    }
}
