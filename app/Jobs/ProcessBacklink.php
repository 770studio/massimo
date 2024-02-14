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
use DOMDocument;

class ProcessBacklink implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public Backlink $backlink)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $response = Http::retry(3, 100)->connectTimeout(10)->get($this->backlink->link_url);
            if ($response->successful())
            {
                $dom = new DOMDocument;
                @$dom->loadHTML($response->body());
                $links = $dom->getElementsByTagName('a');
                foreach ($links as $link) {
                    if (stristr($link->getAttribute('href'),$this->backlink->site->domain))
                    {
                        echo $link->nodeValue." - ";
                        echo $link->getAttribute('href')."\n";
                    }
                }
            }

        } catch(\Illuminate\Http\Client\ConnectionException $e) {

        }
    }

    public function uniqueId(): string
    {
        return $this->backlink->id;
    }
}
