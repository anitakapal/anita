<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupUser extends Model
{
    protected $dateFormat = 'U';
    protected $table = 'group_user';

    protected $fillable = [
        'group_id', 'user_id', 'joined_by',
    ];

    public function members()
    {
        return $this->hasMany(User::class, 'id');
    }
}
