<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\MemberPackages;
use Carbon\Carbon;

class cronJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

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
     $today=Carbon::today();
       $today = $today->toDateString();
       $enddate= MemberPackages::where('status',1)->get()->all();

       foreach ($enddate as $key => $enddate1) {

           if($enddate1->expiredate == $today){
            $enddate1->status=0;
            $enddate1->save();
           }
       }
    }
}
