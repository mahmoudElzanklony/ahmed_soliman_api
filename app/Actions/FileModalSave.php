<?php

namespace App\Actions;

use App\Models\files;

class FileModalSave
{
    public static function make($id,$model_name,$file_name,$file_type){
        files::query()->create([
            'fileable_id'=>$id,
            'fileable_type'=>'App\Models\\'.$model_name,
            'name'=>$file_name,
            'type'=>$file_type,
        ]);
        return true;
    }
}
