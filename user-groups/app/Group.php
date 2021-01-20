<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{

    protected $table = 'groups';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'group_name', 'group_created_by',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];
}
