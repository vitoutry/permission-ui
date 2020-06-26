<?php

namespace Vitoutry\PermissionUI\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
class User extends Authenticatable
{
    use HasRoles,Notifiable;
    // use HasRoles;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    protected $primaryKey = 'id';
    protected $fillable = [
        'username', 'email', 'password','active','is_super_admin','country_id','category_id','parent_id'
    ];
    
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function post()
    {
        return $this->belongsTo('App\Post');
    }

    public function country(){
        return $this->belongsTo('App\Entities\Country');
    }

    public function category(){
        return $this->belongsTo('App\Entities\Category');
    }

    public function categories(){
        return $this->belongsToMany('App\Entities\Category')
                ->withPivot('num_form','country_id','type')
                ->withTimestamps();
    }
    public function finalCategories(){
        return $this->belongsToMany('App\Entities\Category')
                ->withPivot('num_form','country_id','type')
                ->where('type','final')
                ->withTimestamps();
    }

    public function onlineCategories(){
        return $this->belongsToMany('App\Entities\Category')
                ->withPivot('num_form','country_id','type')
                ->where('type','semi')
                ->withTimestamps();
    }

    public function application(){
        return $this->hasOne('App\Entities\Application');
    }

    public function finalJudges(){
        return $this->hasMany('App\Entities\FinalJudge');
    }


    public function scopeActive($query){
        return $query->where('active', true);
    }

    public function result(){
        return $this->hasOne('App\Entities\Result');
    }

    public function judges()
    {
        return $this->hasMany('App\Entities\Judge');
    }

    public static function boot()
    {
        parent::boot();

    }

}
