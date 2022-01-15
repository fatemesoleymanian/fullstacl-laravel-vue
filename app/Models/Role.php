<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['roleName','permission'];
    use HasFactory;
    public function getCreatedAtAttribute($val)
    {
        return verta($val)->format('l d %B Y');
    }
}
