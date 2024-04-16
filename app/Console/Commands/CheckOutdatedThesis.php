<?php

namespace App\Console\Commands;

use App\Models\thesis;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckOutdatedThesis extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-outdated-thesis';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $thesisoutdated = thesis::get()->where('published_at','<', Carbon::now()->subYears(5));
        foreach($thesisoutdated as $thesis){
            $thesisoutdated->status = 1; 
        }
            /*  1 = outdated
            2 = unpsublished
            */
    }
}
