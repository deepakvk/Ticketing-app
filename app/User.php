<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laratrust\Traits\LaratrustUserTrait;
use App\Notifications\MailResetPasswordToken;

class User extends Authenticatable
{
    use LaratrustUserTrait;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'Title','Firstname','Lastname','Status','username','Email', 'password',
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
     * Get the role id from role_user.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
	/**
	 * Send a password reset email to the user
	 */
	public function sendPasswordResetNotification($token)
	{
		$this->notify(new MailResetPasswordToken($token));
	}
}
