<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class categories extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name','info','parent_id'];

    public function parent()
    {
        return $this->belongsTo(categories::class,'id')->withTrashed();
    }



}
