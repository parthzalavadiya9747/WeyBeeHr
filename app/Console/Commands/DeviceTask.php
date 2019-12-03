<?php

namespace App\Console\Commands;

use Illuminate\Http\Request;
use Illuminate\Console\Command;
use App\OTPVerify;
use Carbon\Carbon;
use DB;
use Ixudra\Curl\Facades\Curl;
use App\MemberEnrollment;
use App\Deviceuser;
use App\DeviceEvent;
use App\Deviceseqcount;

class DeviceTask extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:task';

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
         $url = 'http://192.168.1.50';
                    $username = 'admin';
                    $password = '1234';

                     $deviceevent =   DB::table('deviceevent')->get()->last();
                     $deviceseqcountid = DB::table('deviceseqcount')->get()->last();

                     if ($deviceseqcountid) {

                        $deviceseqcounts = $deviceseqcountid->seqno;
                        // echo $deviceseqcounts;

                                $ch = curl_init($url);
                                 
                                
                                curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
                                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml'));
                                curl_setopt($ch, CURLOPT_URL,"http://192.168.1.50/device.cgi/command?action=geteventcount&format=xml");
                           
                              
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);


                                $response = curl_exec($ch);                
                                //$j = json_encode($response);
                                //$s = json_decode($j);

                                $xml_file = simplexml_load_string($response);
                                $json = json_encode($xml_file);
                                $array = json_decode($json,TRUE);

                                $seqcount = $array['Seq-Number'];
                               

                                
                                if($deviceseqcounts != $seqcount) {
                                     
                                      $deviceseqcount = [
                                            'rollovercount'  => $array['Roll-Over-Count'],
                                            'seqno'          => $array['Seq-Number'],
                                        ];

                               
                                        Deviceseqcount::insert($deviceseqcount);
                                }
                                else{
                                        
                                }
                     }
                     else{
                          $ch = curl_init($url);
                                 
                                
                                curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
                                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml'));
                                curl_setopt($ch, CURLOPT_URL,"http://192.168.1.50/device.cgi/command?action=geteventcount&format=xml");
                           
                              
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);


                                $response = curl_exec($ch);                
                                //$j = json_encode($response);
                                //$s = json_decode($j);

                                $xml_file = simplexml_load_string($response);
                                $json = json_encode($xml_file);
                                $array = json_decode($json,TRUE);

                                $seqcount = $array['Seq-Number'];

                                  $deviceseqcount = [
                                            'rollovercount'  => $array['Roll-Over-Count'],
                                            'seqno'          => $array['Seq-Number'],
                                        ];

                               
                                    Deviceseqcount::insert($deviceseqcount);
 
                     }


                     if ($deviceevent) {

                            $seqnumber = $deviceevent->seqno;

                             $t = $seqnumber + 5;
                       

                         if ($t < $deviceseqcounts) {
                               if ($seqnumber < $deviceseqcounts) {

                            
                            for($i=$seqnumber;$i<$t;$i++){

                            echo $i;

         //                curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
         //            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml'));
         //            curl_setopt($ch, CURLOPT_URL,"http://192.168.1.50/device.cgi/events?action=getevent&roll-over-count=0&seq-number=".$i."&no-of-events=1&format=xml");
               
                  
         //            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                                
         //            $response = curl_exec($ch);
         //            //$j = json_encode($response);
         //            //$s = json_decode($j);
                    

         //             $xml_file = simplexml_load_string($response);
                    
         //            $json = json_encode($xml_file);
         //            //echo $json;
         //             $array = json_decode($json,TRUE);
         //             $serialize =serialize($array);

         //             //echo $f;


         // $deviceeventdata = [
         //                        'rollovercount'  => $array['Events']['roll-over-count'],
         //                        'seqno'          => $array['Events']['seq-No'],
         //                        'date'           => $array['Events']['date'],
         //                        'time'           => $array['Events']['time'],
         //                        'eventid'        => $array['Events']['event-id'],
         //                        'detail1'        => $array['Events']['detail-1'],
         //                        'detail2'        => $array['Events']['detail-2'],
         //                        'detail3'        => $array['Events']['detail-3'],
         //                        'detail4'        => $array['Events']['detail-4'],
         //                        'detail5'        => $array['Events']['detail-5'],

         //                    ];

         //         print_r($deviceeventdata);
         //         echo "<br/>";

         //            DeviceEvent::insert($deviceeventdata);
                     }
   
                   }
                        }else{
                            //echo "no thay";
                        }
                     }
                     else{
                         for($i=1;$i<6;$i++){

                            // echo $i;

                        curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml'));
                    curl_setopt($ch, CURLOPT_URL,"http://192.168.1.50/device.cgi/events?action=getevent&roll-over-count=0&seq-number=".$i."&no-of-events=1&format=xml");
               
                  
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                                
                    $response = curl_exec($ch);
                    //$j = json_encode($response);
                    //$s = json_decode($j);
                    

                     $xml_file = simplexml_load_string($response);
                    
                    $json = json_encode($xml_file);
                    //echo $json;
                     $array = json_decode($json,TRUE);
                     $serialize =serialize($array);

                     //echo $f;


         $deviceeventdata = [
                                'rollovercount'  => $array['Events']['roll-over-count'],
                                'seqno'          => $array['Events']['seq-No'],
                                'date'           => $array['Events']['date'],
                                'time'           => $array['Events']['time'],
                                'eventid'        => $array['Events']['event-id'],
                                'detail1'        => $array['Events']['detail-1'],
                                'detail2'        => $array['Events']['detail-2'],
                                'detail3'        => $array['Events']['detail-3'],
                                'detail4'        => $array['Events']['detail-4'],
                                'detail5'        => $array['Events']['detail-5'],

                            ];

                 print_r($deviceeventdata);
                 echo "<br/>";

                    DeviceEvent::insert($deviceeventdata);
                     }

                     }
                    exit;

    }
}
