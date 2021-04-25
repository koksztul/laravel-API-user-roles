<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Venturecraft\Revisionable\RevisionableTrait;
use Fico7489\Laravel\RevisionableUpgrade\Traits\RevisionableUpgradeTrait;

class User extends Authenticatable
{
    use Notifiable;
    use RevisionableTrait;

    protected $revisionCreationsEnabled = true;
    protected $keepRevisionOf = ['fname', 'lname', 'login', 'email', 'type', 'password'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fname', 'lname', 'login', 'email', 'type', 'password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function employee()
    {
        return $this->hasOne(Employee::class);
    }
    public function lecturer()
    {
        return $this->hasOne(Lecturer::class);
    }
}
