<?php

namespace App\Models;
use Cviebrock\EloquentSluggable\Sluggable;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class Post extends Model
{

    use Sluggable;

    const IS_DRAFT = 0;
    const IS_PUBLIC = 1;
    protected $guarded = [];

    protected $fillable = ['title', 'content', 'date', 'description'];

    public function category()
    {

        return $this->belongsTo(Category::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tags()
    {

        return $this->belongsToMany(
            Tag::class, 'posts_tags', 'post_id', 'tag_id'
        );
    }
    public function comments(){
        return $this->hasMany(Comment::class);
    }

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public static function add($fields)
    {
        $post = new static;
        $post->fill($fields);
        $post->user_id =\Auth::user()->id;
        $post->save();
        return $post;
    }

    public function edit($fields)
    {
        $this->fill($fields);
        $this->save();
    }

    /*
    public function remove()
    { $this->removeImage();
        $this->delete();
    }
*/

    public function remove()
    {
        $this->removeImage();
        $this->delete();
    }

    public function removeImage()
    {
        if ($this->image != null) {
            Storage::delete('uploads/' . $this->image);
        }
    }


    public function uploadImage($image)
    {
        if ($image == null) {
            return;
        }



        $this->removeImage();
        $filename = str_random(10) . '.' . $image->extension();
        $image->storeAs('uploads', $filename,'sftp');

        $this->image = $filename;
        $this->save();
    }

    public function getImage()
    {
        if ($this->image == null) {
            return '/img/no-image.png';
        }

        return '/uploads/' . $this->image;

    }


    public function setCategory($id)
    {
        if ($id == null) {
            return;
        }
        $this->category_id = $id;
        $this->save();
    }

    public function setTags($ids)
    {
        if ($ids == null) {
            return;
        }
        $this->tags()->sync($ids);

    }

    public function setDraft()
    {
        $this->status = Post::IS_DRAFT;
        $this->save();
    }

    public function setPublic()
    {
        $this->status = Post::IS_PUBLIC;
        $this->save();
    }

    public function allow()
    {
        $this->status = 1;
        $this->save();
    }

    public function disallow()
    {
        $this->status = 0;
        $this->save();
    }


    public function toggleStatus()
    {
        if ($this->status == 0) {
            return $this->allow();

        }
        return $this->disallow();
    }

    public function setFeatured()
    {
        $this->is_featured = 1;
        $this->save();
    }

    public function setStandart()
    {
        $this->is_standart = 0;
        $this->save();
    }

    public function toogleFeatured($value)
    {
        if ($value == 0) {
            return $this->setStandart();
        } else {
            return $this->setFeatured();
        }
    }

    public function setDateAttribute($value)
    {

        $date = Carbon::createFromFormat('d/m/y', $value)->format('Y-m-d');

        $this->attributes['date'] = $date;
    }

    public function getDateAttribute($value)
    {

        $date = Carbon::createFromFormat('Y-m-d', $value)->format('d/m/y');

        return $date;
    }


    public function getCategoryTitle()
    {
        if ($this->category != null) {
            return $this->category->title;
        }
        return 'Нет категории';
    }

    public function getTagsTitles()
    {
        if (!$this->tags->isEmpty()) {
            return implode(',', $this->tags->pluck('title')->all());
        }
        return 'Нет тэгов';
    }
    public function getDate(){

   return Carbon::createFromFormat('d/m/y',$this->date)->format('F d,Y');
    }

    public function hasPrevious()
    {
        return self::where('id', '<', $this->id)->max('id');
    }

    public function getPrevious()
    {
        $postID = $this->hasPrevious(); //ID
        return self::find($postID);
    }

    public function hasNext()
    {
        return self::where('id', '>', $this->id)->min('id');
    }

    public function getNext()
    {
        $postID = $this->hasNext();
        return self::find($postID);
    }

    public function related(){
        return self::all()->except($this->id);
    }
    public function hasCategory()
    {
        return $this->category != null ? true : false;
    }

    public function getComments()
    {
        return $this->comments()->where('status', 1)->get();
    }

}
