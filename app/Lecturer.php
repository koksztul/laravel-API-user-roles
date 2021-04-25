<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lecturer extends Model
{
    protected $fillable = [
        'phone_number',
        'education',
        'user_id'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
