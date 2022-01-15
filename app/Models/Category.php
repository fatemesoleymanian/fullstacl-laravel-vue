<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['id','categoryName','IconImage'];
    use HasFactory;
    public function getCreatedAtAttribute($val)
    {
        return verta($val)->format('l d %B Y');
    }
}
