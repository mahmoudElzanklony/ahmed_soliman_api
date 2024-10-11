<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class files extends Model
{
    use HasFactory;

    protected $fillable = ['fileable_id','fileable_type','name','type'];

    public function fileable()
    {
        return $this->morphTo();
    }
}
