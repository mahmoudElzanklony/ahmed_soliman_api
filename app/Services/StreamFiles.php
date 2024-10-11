<?php

namespace App\Services;

use App\Models\subjects_videos;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StreamFiles
{
    public static function stream($filePath)
    {

        if (!Storage::disk('wasabi')->exists($filePath)) {
            return 'File not found';
        }

        $fileUrl = Storage::disk('wasabi')->temporaryUrl(
            $filePath, now()->addMinutes(1000) // URL expires in 3 hours
        );

        return $fileUrl;



    }


}
