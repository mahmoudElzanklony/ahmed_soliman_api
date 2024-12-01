<?php

namespace App\Jobs;

use App\Models\files;
use App\Models\images;
use App\Models\subjects_videos;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class GenerateExpiringWasabiUrls implements ShouldQueue
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
        $files = files::query()->get();

        foreach ($files as $file) {
            // Generate a presigned URL with a 12-hour expiration
            $expiration = Carbon::now()->addHours(11);
            $filePath = 'files/' . $file->name;
            if (Storage::disk('wasabi')->exists($filePath)) {
                $temporaryUrl = Storage::disk('wasabi')
                    ->temporaryUrl($filePath, $expiration); // Assuming `path` is the column for the file path

                // Update the wasbi_url column in the database
                $file->wasbi_url = $temporaryUrl;
                $file->save(); // Use save instead of update
            }



        }
    }
}
