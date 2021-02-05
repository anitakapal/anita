<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $dateFormat = 'U';
    protected $table = 'groups';
    protected $fillable = [
        'name', 'created_by', 'type',
    ];

    public function members()
    {
        return $this->hasMany(GroupUser::class, 'group_id');
    }
}
