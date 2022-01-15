<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
    use HasFactory;
    protected $guarded=['id'];

    public function reply()
    {
        return $this->hasMany(Replies::class,'comment_id')->where('isPublished',1);
    }

    public function user()
    {
        return $this->belongsTo(User::class)->select('id', 'fullName', 'profilePic');
    }
    public function getUpdatedAtAttribute($val)
    {
        return verta($val)->format('l d %B Y');
    }

}
