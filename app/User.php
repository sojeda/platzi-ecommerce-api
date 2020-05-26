<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laraveles\Rating\Contracts\Rating as RatingAlias;
use Laraveles\Rating\Traits\CanBeRated;
use Laraveles\Rating\Traits\CanRate;

class User extends Authenticatable implements MustVerifyEmail, RatingAlias
{
    use Notifiable, HasApiTokens, CanRate, CanBeRated;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
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

    public function name(): string
    {
        // TODO: Implement name() method.
    }
}
