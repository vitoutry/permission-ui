<?php

namespace Vitoutry\PermissionUI\Models;

use Illuminate\Database\Eloquent\Model; 

class Role extends Model{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'roles';

    protected $primaryKey = 'id';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [

    ];


    public static function boot()
    {
        parent::boot();

    }

}
