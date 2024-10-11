<?php


namespace App\Http\Traits;


use App\Actions\ImageModalSave;
use App\Models\files;
use App\Services\Messages;
use Aws\S3\S3Client;
use Illuminate\Support\Facades\Storage;
use FFMpeg;
use FFMpeg\Format\Video\X264;
use GuzzleHttp\Psr7\Stream;
use GuzzleHttp\Psr7\CachingStream;

trait upload_image
{
    public function upload($file,$folder_name,$type = 'one'){
        $valid_extensions = ['png','jpg','jpeg','gif','svg','webp'];
        if($type == 'one') {
            if (in_array($file->getClientOriginalExtension(), $valid_extensions)) {
                $name = time().rand(0,9999999999999). '_image.' . $file->getClientOriginalExtension();

                $filePath = $folder_name.'/'. $name;
                if(env('WAS_STATUS') == 1) {
                    Storage::disk('wasabi')
                        ->put(
                            $filePath,
                            file_get_contents($file->getRealPath())
                        );
                }else{
                    $file->move(public_path('files/' . $folder_name), $name);
                }

                return $name;
            } else {
                return Messages::error('image extension is not correct');
            }
        }
    }

    public  function check_upload_image($image,$folder_name,$model_id ,$model_name)
    {
        if($image != null){
            $name = $folder_name.'/'.$this->upload($image,$folder_name);
        }else{
            $name = $folder_name.'/default.png';
        }
        files::query()
            ->where('imageable_id','=',$model_id)
            ->where('imageable_type','=','App\Models\\'.$model_name)->delete();
        ImageModalSave::make($model_id,$model_name,$name);
    }

    public function upload_file($file)
    {
        $name = time() . rand(0, 9999999999999) . '_file.' . $file->getClientOriginalExtension();
        $filePath = 'files/' . $name;


        $s3Client = new S3Client([
            'version' => 'latest',
            'region' => env('WAS_DEFAULT_REGION'),
            'endpoint' => env('WAS_ENDPOINT'),
            'credentials' => [
                'key' => env('WAS_ACCESS_KEY_ID'),
                'secret' => env('WAS_SECRET_ACCESS_KEY'),
            ],
        ]);

        try {
            // Get the file stream
            $fileStream = fopen($file->getRealPath(), 'r');

            // Determine the MIME type of the file (e.g., 'video/mp4')
            $mimeType = $file->getMimeType();

            // Upload the file directly to Wasabi with correct content type
            $result = $s3Client->putObject([
                'Bucket' => env('WAS_BUCKET'),
                'Key' => $filePath,
                'Body' => $fileStream,
                'ContentType' => $mimeType,  // Set correct MIME type for the file
                'ACL' => 'public-read', // Public access if needed
            ]);

            // Close the file stream
            fclose($fileStream);



        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
        return $name;

    }


}
