<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    protected $guarded = ['id', 'subscriptionplanid'];
    protected $primaryKey = 'subscriptionplanid';
    public $incrementing = false;
    protected $keyType = 'string';

    public function getRouteKeyName()
    {
        return 'subscriptionplanid';
    }
}
