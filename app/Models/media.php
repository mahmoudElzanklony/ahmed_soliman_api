<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class media extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','category_id','name','info'];

    public function category()
    {
        return $this->belongsTo(categories::class,'category_id');
    }

    public function file()
    {
        return $this->morphOne(files::class,'fileable');
    }
}
