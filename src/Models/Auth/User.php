<?php

namespace Devel\Models\Auth;

use Illuminate\Notifications\Notifiable;
use Devel\Models\Authenticatable;
use Devel\Traits\HasPermissions;
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
        'roles.permissions',
        'permissions',
    ];

    protected static function boot()
    {
        parent::boot();

        static::updating(function ($user) {
            // The root user can only be edited by the root themselves
            if (!auth()->id() || ($user->roles->contains('root') && auth()->id() !== $user->id)) {
                return false;
            }
        });

        static::deleting(function ($user) {
            if ($user->roles->contains('root')) {
                return false;
            }
        });
    }

    /**
     * A user has many roles
     *
     * @return void
     */
    public function roles()
    {
        return $this->belongsToMany(
            Role::class,
            'devel_user_role',
            'user_id',
            'role'
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
            'devel_user_permission',
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
