<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Payment extends Model
{
    protected $guarded = ['id', 'paymentid'];
    protected $primaryKey = 'paymentid';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $appends = ['expired'];

    public function getExpiredAttribute()
    {
        return false;
        /*return !(
            $this->attributes['expirydate'] >= date('Y-m-d') &&
            $this->attributes['responsecode'] == '01'
        );*/
    }

    public function getRouteKeyName()
    {
        return 'paymentid';
    }

    public function payer()
    {
        return $this->belongsTo(Student::class, 'payerid', 'userid');
    }
}
