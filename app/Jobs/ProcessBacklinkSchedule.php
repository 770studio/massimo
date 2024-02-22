<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Support\Facades\Http;
use App\Models\Backlink;


class ProcessBacklinkSchedule implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $backlinks= BackLink::where('last_checked_at','<=', date("Y-m-d H:i:s",strtotime('24 hours ago')))->orWhere('last_checked_at', '=', null)->get();
        if($backlinks->count()>0) foreach($backlinks as $backlink)
        {
            ProcessBacklink::dispatch($backlink);
        }
    }
}
