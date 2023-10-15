<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupSession extends Model
{
    protected $guarded = ['id', 'groupsessionid'];
    protected $primaryKey = 'groupsessionid';
    public $incrementing = false;
    protected $keyType = 'string';


    public function getRouteKeyName()
    {
        return 'groupsessionid';
    }

    public function groupAttendances()
    {
        return $this->hasMany(GroupAttendance::class, 'groupsessionid', 'groupsessionid');
    }

    public function group()
    {
        return $this->belongsTo(Group::class, 'groupid', 'groupid');
    }
}
