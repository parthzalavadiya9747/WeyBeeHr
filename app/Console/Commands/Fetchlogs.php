<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use App\Member;
use App\User;
use App\Deviceuser;
use Ixudra\Curl\Facades\Curl;
use App\MemberEnrollment;
use App\DeviceEvent;
use App\Deviceseqcount;
use App\Employee;
use App\Actionlog;

class Fetchlogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:fetchlogs';

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

        $deviceinfo_data = DB::table('deviceinfo')
                              //->where('devicetype','independent')
                              ->where('status','1')
                              ->get()->all();
                    



     

        foreach($deviceinfo_data as $deviceinfo){
          $url = 'http://'.$deviceinfo->ipaddress.'';
          $portno = $deviceinfo->portno;
          $username = $deviceinfo->username;
          $password = $deviceinfo->password;

         /*try {*/      

                    $deviceevent =   DB::table('deviceevent')->where('deviceid', $deviceinfo->deviceinfoid)->orderBy('deviceeventid', 'desc')->get()->first();
                    $deviceseqcountid = DB::table('deviceseqcount')->where('deviceid', $deviceinfo->deviceinfoid)->orderBy('deviceseqcountid', 'desc')->get()->first();

                if (!empty($deviceseqcountid)) {

                        $deviceseqcounts = $deviceseqcountid->seqno;
                      
                                $ch = curl_init($url);
                                                                 
                                curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
                                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml'));
                                curl_setopt($ch, CURLOPT_URL,'http://'.$deviceinfo->ipaddress.':'.$deviceinfo->portno.'/device.cgi/command?action=geteventcount&format=xml');
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                $response = curl_exec($ch);

                                $xml_file = simplexml_load_string($response);
                                $json = json_encode($xml_file);
                                $array = json_decode($json,TRUE);

                                $seqcount = $array['Seq-Number'];
                                
                                if($deviceseqcounts != $seqcount) {
                                     
                                      $deviceseqcount = [
                                           'deviceid' => $deviceinfo->deviceinfoid,
                                            'rollovercount'  => $array['Roll-Over-Count'],
                                            'seqno'          => $array['Seq-Number'],
                                        ];

                                        Deviceseqcount::insert($deviceseqcount);
                                        $this->test($request, $deviceinfo->deviceinfoid);
                                }
                     }else{

                       
                          $ch = curl_init($url);                               
                                
                                curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
                                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml'));
                                curl_setopt($ch, CURLOPT_URL,"http://".$deviceinfo->ipaddress.':'.$deviceinfo->portno."/device.cgi/command?action=geteventcount&format=xml");
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                $response = curl_exec($ch);                

                                $xml_file = simplexml_load_string($response);
                                $json = json_encode($xml_file);
                                $array = json_decode($json,TRUE);

                                $seqcount = $array['Seq-Number'];

                                  $deviceseqcount = [
                                           'deviceid' => $deviceinfo->deviceinfoid,
                                            'rollovercount'  => $array['Roll-Over-Count'],
                                            'seqno'          => $array['Seq-Number'],
                                        ];

                                    Deviceseqcount::insert($deviceseqcount);
                                    $this->test($request, $deviceinfo->deviceinfoid);
                                }
                                
                   

                    /*}catch (\Exception $e) {

                                echo "Your Device Not connected !";
                                
        }*/
      }
     
        
        //return redirect('viewfetchlogs');
    }

  public function test(Request $request, $deviceidins){

      $deviceevent =   DB::table('deviceevent')->where('deviceid', $deviceidins)->orderBy('deviceeventid', 'desc')->get()->first();
      $deviceseqcountid = DB::table('deviceseqcount')->where('deviceid', $deviceidins)->orderBy('deviceseqcountid', 'desc')->get()->first();

    if ($deviceevent) {

                            $cou = [];
                            $seqnumber = $deviceevent->seqno;
                            $seqrollovercount = $deviceevent->rollovercount;
                            $dscrolloc = $deviceseqcountid->rollovercount;
                            $dscrollocseq3 = $deviceseqcountid->seqno;
                            $deviceinfo = DB::table('deviceinfo')
                                                ->where('deviceinfoid', $deviceidins)
                                                  ->where('status','1')
                                                  ->first();

                            for($i = 0; $i < 2; $i++)
                            {
                               
                                if($seqnumber > 49999)
                                {

                                    
                                    for($r = $seqnumber; $r <= 50000; $r++)
                                    {
                                        // $first = 50000;
                                        // array_push($cou, $first);
                                             $seqnumber = 1;
                                         $dscrolloc = $dscrolloc + 1;
                                        // print_r($dscrolloc);
                    

                                        $url = 'http://'.$deviceinfo->ipaddress.'';
                                         $username = $deviceinfo->username;
                                         $password = $deviceinfo->password;

                                         $ch = curl_init($url);
                                 
                                
                                curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
                                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml'));
                                curl_setopt($ch, CURLOPT_URL,"http://".$deviceinfo->ipaddress.":".$deviceinfo->portno."/device.cgi/events?action=getevent&roll-over-count=".$dscrolloc."&seq-number=".$seqnumber."&no-of-events=1&format=xml");
                           
                              
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);


                                            $response = curl_exec($ch);
                                            $xml_file = simplexml_load_string($response);
                                            $json = json_encode($xml_file);
                                            $array = json_decode($json,TRUE);
                                            $serialize =serialize($array);
                                           $dated = str_replace('/', '-', $array['Events']['date']);
                                            // exit;
                                            $deviceeventdata = [
                                                'deviceid' => $deviceinfo->deviceinfoid,
                                                'rollovercount'  =>  $array['Events']['roll-over-count'],
                                                'seqno'          => !empty($array['Events']['seq-No']) ? $array['Events']['seq-No'] : '',
                                                'date'           => !empty(date('Y-m-d', strtotime($dated))) ? date('Y-m-d', strtotime($dated)) : '' ,
                                                'time'           => !empty(date('H:i:s', strtotime($array['Events']['time']))) ? date('H:i:s', strtotime($array['Events']['time'])) : '' ,
                                                'eventid'        => !empty($array['Events']['event-id']) ? $array['Events']['event-id'] : '' ,
                                                'detail1'        => !empty($array['Events']['detail-1']) ? $array['Events']['detail-1'] : '' ,
                                                'detail2'        => !empty($array['Events']['detail-2']) ? $array['Events']['detail-2'] : '' ,
                                                'detail3'        => !empty($array['Events']['detail-3']) ? $array['Events']['detail-3'] : '' ,
                                                'detail4'        => !empty($array['Events']['detail-4']) ? $array['Events']['detail-4'] : '' ,
                                                'detail5'        => !empty($array['Events']['detail-5']) ? $array['Events']['detail-5'] : '' ,

                                            ];                                        
                                              DeviceEvent::insert($deviceeventdata);
                                                $seqnumber++; 
                                    }

                                }

                                else 
                                {
                                  
                                     $lastec = DeviceEvent::get()->last();

                                    if ($lastec->seqno != $dscrollocseq3) {
                                            
                                      
                                        for($k = $lastec->seqno; $k<$dscrollocseq3; $k++)
                                        {
                                              $second = $k;
                                              $second = $second + 1;
                                              
                                            // array_push($cou, $second);
                                             

                                             $url = 'http://'.$deviceinfo->ipaddress.'';
                                         $username = $deviceinfo->username;
                                         $password = $deviceinfo->password;


                                             $ch = curl_init($url);                               
                                
                                            curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
                                            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml'));
                                       
                                            curl_setopt($ch, CURLOPT_URL,"http://".$deviceinfo->ipaddress.":".$deviceinfo->portno."/device.cgi/events?action=getevent&roll-over-count=".$dscrolloc."&seq-number=".$second."&no-of-events=1&format=xml");
                                       
                                          
                                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);


                                            $response = curl_exec($ch);
                                            $xml_file = simplexml_load_string($response);
                                            $json = json_encode($xml_file);
                                            $array = json_decode($json,TRUE);
                                            $serialize =serialize($array);

                                            //dd($array['Events']['roll-over-count']);
                                           $dated = str_replace('/', '-', $array['Events']['date']);
                                            // exit;
                                            $deviceeventdata = [
                                                'deviceid' => $deviceinfo->deviceinfoid,
                                                'rollovercount'  =>  $array['Events']['roll-over-count'],
                                                'seqno'          => !empty($array['Events']['seq-No']) ? $array['Events']['seq-No'] : '',
                                                'date'           => !empty(date('Y-m-d', strtotime($dated))) ? date('Y-m-d', strtotime($dated)) : '' ,
                                                'time'           => !empty(date('H:i:s', strtotime($array['Events']['time']))) ? date('H:i:s', strtotime($array['Events']['time'])) : '' ,
                                                'eventid'        => !empty($array['Events']['event-id']) ? $array['Events']['event-id'] : '' ,
                                                'detail1'        => !empty($array['Events']['detail-1']) ? $array['Events']['detail-1'] : '' ,
                                                'detail2'        => !empty($array['Events']['detail-2']) ? $array['Events']['detail-2'] : '' ,
                                                'detail3'        => !empty($array['Events']['detail-3']) ? $array['Events']['detail-3'] : '' ,
                                                'detail4'        => !empty($array['Events']['detail-4']) ? $array['Events']['detail-4'] : '' ,
                                                'detail5'        => !empty($array['Events']['detail-5']) ? $array['Events']['detail-5'] : '' ,

                                            ];                                        
                                             // dd($deviceeventdata);

                                              DeviceEvent::insert($deviceeventdata);
                                                // $second++;
   
                                                }

                                           //return redirect('viewfetchlogs');
                                    
                                          }
                                       }
                                       // -------else complited----
                                    }
                                    // ----for complited------
                                  }
                                  //if completed
                                  else{
                                    
                                     $seqnumber = $deviceseqcountid->seqno;
                                      $seqrollovercount = $deviceseqcountid->rollovercount;
                                      $dscrolloc = $deviceseqcountid->rollovercount;
                                      $dscrollocseq3 = $deviceseqcountid->seqno;
                                      $deviceinfo = DB::table('deviceinfo')
                                                  ->where('status','1')
                                                  ->where('deviceinfoid', $deviceidins)
                                                  ->first();

                                    for($i = 0; $i < 2; $i++)
                                    {
                                     
                                      if($seqnumber > 49999)
                                      {
                                        
                                          //echo "fsdfsdfsdfds";exit;
                                          for($r = $seqnumber; $r <= 50000; $r++)
                                          {
                                            
                                              // $first = 50000;
                                              // array_push($cou, $first);
                                                   $seqnumber = 1;
                                               $dscrolloc = $dscrolloc + 1;
                                              // print_r($dscrolloc);
                          

                                              $url = 'http://'.$deviceinfo->ipaddress.'';
                                               $username = $deviceinfo->username;
                                               $password = $deviceinfo->password;

                                               $ch = curl_init($url);
                                       
                                      
                                      curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
                                      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml'));
                                      curl_setopt($ch, CURLOPT_URL,"http://".$deviceinfo->ipaddress.":".$deviceinfo->portno."/device.cgi/events?action=getevent&roll-over-count=".$dscrolloc."&seq-number=".$seqnumber."&no-of-events=1&format=xml");
                                 
                                    
                                      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);


                                                  $response = curl_exec($ch);
                                                  $xml_file = simplexml_load_string($response);
                                                  $json = json_encode($xml_file);
                                                  $array = json_decode($json,TRUE);
                                                  $serialize =serialize($array);
                                                 $dated = str_replace('/', '-', $array['Events']['date']);
                                                  // exit;
                                                  $deviceeventdata = [
                                                      'deviceid' => $deviceinfo->deviceinfoid,
                                                      'rollovercount'  =>  $array['Events']['roll-over-count'],
                                                      'seqno'          => !empty($array['Events']['seq-No']) ? $array['Events']['seq-No'] : '',
                                                      'date'           => !empty(date('Y-m-d', strtotime($dated))) ? date('Y-m-d', strtotime($dated)) : '' ,
                                                      'time'           => !empty(date('H:i:s', strtotime($array['Events']['time']))) ? date('H:i:s', strtotime($array['Events']['time'])) : '' ,
                                                      'eventid'        => !empty($array['Events']['event-id']) ? $array['Events']['event-id'] : '' ,
                                                      'detail1'        => !empty($array['Events']['detail-1']) ? $array['Events']['detail-1'] : '' ,
                                                      'detail2'        => !empty($array['Events']['detail-2']) ? $array['Events']['detail-2'] : '' ,
                                                      'detail3'        => !empty($array['Events']['detail-3']) ? $array['Events']['detail-3'] : '' ,
                                                      'detail4'        => !empty($array['Events']['detail-4']) ? $array['Events']['detail-4'] : '' ,
                                                      'detail5'        => !empty($array['Events']['detail-5']) ? $array['Events']['detail-5'] : '' ,

                                                  ];                                        
                                                    DeviceEvent::insert($deviceeventdata);
                                                      $seqnumber++; 
                                          }
                                          // for loop completed
                                      }
                                      // if completed
                                      else 
                                {
                                
                                     $lastec = $deviceseqcountid->seqno;

                                    if ($lastec == $dscrollocseq3) {
                                            

                                        for($k = $lastec - 1; $k<$dscrollocseq3; $k++)
                                        {
                                              $second = $k;
                                              $second = $second + 1;
                                              
                                            // array_push($cou, $second);
                                             

                                             $url = 'http://'.$deviceinfo->ipaddress.'';
                                         $username = $deviceinfo->username;
                                         $password = $deviceinfo->password;
                                            /*echo "http://".$deviceinfo->ipaddress.":".$deviceinfo->portno."/device.cgi/events?action=getevent&roll-over-count=".$dscrolloc."&seq-number=".$second."&no-of-events=1&format=xml";
*/
                                             $ch = curl_init($url);                               
                                
                                            curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
                                            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml'));
                                       
                                            curl_setopt($ch, CURLOPT_URL,"http://".$deviceinfo->ipaddress.":".$deviceinfo->portno."/device.cgi/events?action=getevent&roll-over-count=".$dscrolloc."&seq-number=".$second."&no-of-events=1&format=xml");
                                       
                                          
                                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);


                                            $response = curl_exec($ch);
                                           
                                            $xml_file = simplexml_load_string($response);
                                            $json = json_encode($xml_file);
                                            $array = json_decode($json,TRUE);
                                            $serialize =serialize($array);

                                             // print_r($array);
                                           $dated = str_replace('/', '-', $array['Events']['date']);
                                             // print_r($dated);exit;
                                            $deviceeventdata = [
                                                'deviceid' => $deviceinfo->deviceinfoid,
                                                'rollovercount'  =>  $array['Events']['roll-over-count'],
                                                'seqno'          => !empty($array['Events']['seq-No']) ? $array['Events']['seq-No'] : '',
                                                'date'           => !empty(date('Y-m-d', strtotime($dated))) ? date('Y-m-d', strtotime($dated)) : '' ,
                                                'time'           => !empty(date('H:i:s', strtotime($array['Events']['time']))) ? date('H:i:s', strtotime($array['Events']['time'])) : '' ,
                                                'eventid'        => !empty($array['Events']['event-id']) ? $array['Events']['event-id'] : '' ,
                                                'detail1'        => !empty($array['Events']['detail-1']) ? $array['Events']['detail-1'] : '' ,
                                                'detail2'        => !empty($array['Events']['detail-2']) ? $array['Events']['detail-2'] : '' ,
                                                'detail3'        => !empty($array['Events']['detail-3']) ? $array['Events']['detail-3'] : '' ,
                                                'detail4'        => !empty($array['Events']['detail-4']) ? $array['Events']['detail-4'] : '' ,
                                                'detail5'        => !empty($array['Events']['detail-5']) ? $array['Events']['detail-5'] : '' ,

                                            ];                                        
                                              // dd($deviceeventdata);

                                              DeviceEvent::insert($deviceeventdata);
                                                // $second++;
   
                                                }

                                           //return redirect('viewfetchlogs');
                                    
                                          }
                                       }
                                    }
                                    //for completed
                                  }
                                  //else completed

                         
     }
}
