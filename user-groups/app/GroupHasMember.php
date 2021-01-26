<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupHasMember extends Model
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
    public function members()
    {
        return $this->hasMany(User::class, 'id');
    }
}
