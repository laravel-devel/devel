<?php

namespace Devel\Models\Auth;

use Devel\Models\Model;
use Devel\Traits\HasPermissions;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    use HasPermissions;

    public $table = 'devel_user_roles';

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

    protected $casts = [
        'default' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($role) {
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
     * @return BelongsToMany<\Devel\Models\Auth\Permission, $this>
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            Permission::class,
            'devel_user_role_permission',
            'role',
            'permission'
        );
    }
}
