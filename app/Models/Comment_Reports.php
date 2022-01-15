<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment_Reports extends Model
{
    use HasFactory;
    protected $guarded=['id'];
    public function getCreatedAtAttribute($val)
    {
        return verta($val)->format('l d %B Y');
    }
}
