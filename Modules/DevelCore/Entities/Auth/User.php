<?php

namespace Modules\DevelCore\Entities\Auth;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Modules\DevelCore\Traits\HasPermissions;
use Modules\DevelCore\Traits\Sortable;
use Modules\DevelDashboard\Notifications\ResetPasswordNotification;

class User extends Authenticatable
{
    use Notifiable;
    use HasPermissions;
    use Sortable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * A user has many roles
     *
     * @return void
     */
    public function roles()
    {
        return $this->belongsToMany(
            Role::class,
            'user_role',
            'user_id',
            'role',
            'id',
            'key'
        );
    }

    /**
     * A user has many individual permissions
     *
     * @return void
     */
    public function permissions()
    {
        return $this->belongsToMany(
            Permission::class,
            'user_permission',
            'user_id',
            'permission',
            'id',
            'key'
        );
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }
}
