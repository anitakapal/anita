<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupHasUser extends Model
{

    protected $table = 'group_has_members';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'group_id', 'member_id', 'joined_by',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];
}
