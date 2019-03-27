<?php

namespace App;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use Notifiable;
//protected $guarded=[];

const IS_BANNED=1;
const AKTIVE=0;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'avatar'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    public function posts(){
        return $this->hasMany(Post::class);
    }
    public function comments(){
        return $this->hasMany(Comment::class);
    }

    public static function add($fields){
        $user=new static;
        $user->fill($fields);

        $user->save();
        return $user;
    }
    public function edit($fields){
        $this->fill($fields);
        if ($fields['password']!==null){
            $this->password=bcrypt($fields['password']);
        }
               $this->save();
    }
    public function remove()
    { $this->removeAvatar();
        $this->delete();

    }
    public function uploadAvatar($image){

        if ($image==null){return;}

      $this->removeAvatar();

        $filename=str_random(10).'.'.$image->extension();
        $image->storeAs('uploads',$filename);
        $this->avatar=$filename;
        $this->save();
    }
    public function getImage()
    {
        if($this->avatar == null)
        {
            return '/img/no-image.png';
        }

        return '/uploads/' . $this->avatar;
    }


    public function makeAdmin(){
        $this->is_admin=1;
        $this->save();
    }
    public function makeNormal(){
    $this->is_admin=0;
    $this->save();
    }
    public function toggleAdmin($value){
        if ($value==0){
            return $this->makeNormal();
        }
        return $this->makeAdmin();
    }


    public function ban(){
        return $this->status=User::IS_BANNED;
        $this->save();
    }
    public function unban(){
        return $this->status=User::AKTIVE;
        $this->save();
    }
    public function toggleBan($value){
        if ($value==0){
           return $this->unban();
        }
        return $this->ban();
    }
    public function generatePassword($password){
        if ($password==!null){
            $this->password=bcrypt($password);
            $this->save();
        }
    }
    public function removeAvatar(){
        if ($this->avatar!=null){
            Storage::delete('uploads/'.$this->avatar);
        }
    }
}
