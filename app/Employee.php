<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'user_id',
        'mail_voivodship',
        'mail_city',
        'mail_postcode',
        'mail_street',
        'mail_number',
        'addr_voivodship',
        'addr_city',
        'addr_postcode',
        'addr_street',
        'addr_number',
    ];

    public function role()
    {
        return $this->belongsTo(User::class);
    }
}
