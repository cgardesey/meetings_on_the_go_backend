<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $guarded = ['id'];
    protected $primaryKey = 'groupid';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $hidden = ['group_member'];

    public function getRouteKeyName()
    {
        return 'groupid';
    }

    public function groupMembers()
    {
        return $this->hasMany(GroupMember::class, 'groupid', 'groupid');
    }

    public function groupSessions()
    {
        return $this->hasMany(GroupSession::class, 'groupid', 'groupid');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creatorid', 'userid');
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'group_members', 'groupid', 'userid', 'groupid', 'userid')
            ->using(GroupMember::class)
            ->as('groupMember')
            ->withPivot('id', 'groupmemberid', 'admin')
            ->withTimestamps();
    }

    public function chats()
    {
        return $this->hasMany(Chat::class, 'groupid', 'groupid');
    }
}
