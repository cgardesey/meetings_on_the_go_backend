<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupAttendance extends Model
{
    protected $guarded = ['id', 'groupattendanceid'];
    protected $primaryKey = 'groupattendanceid';
    public $incrementing = false;
    protected $keyType = 'string';


    public function getRouteKeyName()
    {
        return 'groupattendanceid';
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'userid', 'userid');
    }

    public function groupSession()
    {
        return $this->belongsTo(GroupSession::class, 'groupsessionid', 'groupsessionid');
    }
}
