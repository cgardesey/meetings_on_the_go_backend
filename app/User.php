<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $guarded = ['id', 'userid'];
    protected $primaryKey = 'userid';
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['api_token'];


    public function sentChatMessages()
    {
        return $this->hasMany(Chat::class, 'senderid', 'userid');
    }

    public function receivedChatMessages()
    {
        return $this->hasMany(Chat::class, 'recepientid', 'userid');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'payerid', 'userid');
    }

    public function createdGroups()
    {
        return $this->hasMany(Group::class, 'creatorid', 'userid');
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_members', 'memberid', 'groupid', 'userid', 'groupid')
            ->using(GroupMember::class)
            ->as('groupMember')
            ->withPivot('id', 'groupmemberid', 'admin')
            ->withTimestamps();
    }

    public function groupAttendances()
    {
        return $this->hasMany(GroupAttendance::class, 'userid','userid');
    }
}
