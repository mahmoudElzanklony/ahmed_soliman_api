<?php


namespace App\Actions;


use App\Models\files;

class ImageModalSave
{
    public static function make($id,$model_name,$image_file){
        files::query()->create([
            'imageable_id'=>$id,
            'imageable_type'=>'App\Models\\'.$model_name,
            'name'=>$image_file,
        ]);
        return true;
    }
}
