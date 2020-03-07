<?php

namespace Devel\Core\Entities\Auth;

use Devel\Core\Entities\Model;
use Devel\Core\Traits\HasPermissions;

class Role extends Model
{
    use HasPermissions;

    public $table = 'user_roles';
    
    protected $primaryKey = 'key';
    protected $keyType = 'string';

    public $incrementing = false;
    
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'key',
        'name',
        'default',
    ];

    protected $searchable = [
        'key',
        'name',
    ];

    protected $with = [
        'permissions',
    ];

    protected static function boot()
    {
        parent::boot();

        static::updating(function ($role) {
            // The 'root' role cannot be changed
            if ($role->getOriginal('key') === 'root') {
                $role->key = 'root';
                $role->name = 'Root';
            }

            // Only one role can be default at any given moment
            if ($role->default && $role->default != $role->getOriginal('default')) {
                static::where('default', true)->update(['default' => false]);
            }
        });

        static::deleting(function ($role) {
            if ($role->key === 'root') {
                return false;
            }
        });
    }

    /**
     * A role has many permissions
     *
     * @return void
     */
    public function permissions()
    {
        return $this->belongsToMany(
            Permission::class,
            'user_role_permission',
            'role',
            'permission',
        );
    }
}
