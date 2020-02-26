<?php

namespace Modules\DevelCore\Entities\Auth;

use Illuminate\Notifications\Notifiable;
use Modules\DevelCore\Entities\Authenticatable;
use Modules\DevelCore\Traits\HasPermissions;
use Modules\DevelDashboard\Notifications\ResetPasswordNotification;

class User extends Authenticatable
{
    use Notifiable;
    use HasPermissions;

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
        'email_verified_at',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $searchable = [
        'name',
        'email',
    ];

    protected $with = [
        'roles',
        'permissions',
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
