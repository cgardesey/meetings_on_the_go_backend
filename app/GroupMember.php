<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class GroupMember extends Pivot
{
    protected $guarded = ['id', 'groupmemberid'];
    protected $primaryKey = 'groupmemberid';
    public $incrementing = false;
    protected $keyType = 'string';

    public $table = "group_members";

    public function getRouteKeyName()
    {
        return 'groupmemberid';
    }

    public function group()
    {
        return $this->belongsTo(Group::class, 'groupid', 'groupid');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'userid', 'userid');
    }
}
