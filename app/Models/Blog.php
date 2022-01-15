<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Blog extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'post', 'post_excerpt', 'slug', 'user_id', 'featuredImage', 'metaDescription', 'views', 'jsonData'];
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }
    public function getCreatedAtAttribute($val)
    {
        return verta($val)->format('l d %B Y');
    }

    //make this is to => this-is
    public function tag()
    {
        return $this->belongsToMany(Tag::class,Blogtag::class);
    }
    public function category()
    {
        return $this->belongsToMany(Category::class,Blogcategory::class);
    }
}
