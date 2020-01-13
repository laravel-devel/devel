<?php

namespace Modules\DevelCore\Entities\Auth;

use Modules\DevelCore\Entities\Model;
use Modules\DevelCore\Traits\HasPermissions;

class Role extends Model
{
    use HasPermissions;

    public $table = 'user_roles';

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'role',
        'default',
    ];

    protected $searchable = [
        'key',
        'name',
    ];

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
            'key',
            'key'
        );
    }
}
