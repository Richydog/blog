<?php

namespace App\Models;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use Sluggable;
protected $guarded=[];
    public function posts( ){
        return $this->belongsToMany(
            Post::class,
            'posts_tags',
            'tag_id',
            'post_id'
        );
    }

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

}
