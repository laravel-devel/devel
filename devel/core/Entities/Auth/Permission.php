<?php

namespace Devel\Core\Entities\Auth;

use Devel\Core\Entities\Model;

class Permission extends Model
{
    public $table = 'user_permissions';

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
    ];

    protected $searchable = [
        'key',
        'name',
    ];

    protected $hidden = ['pivot'];

    /**
     * Get list of permissions grouped into groups
     *
     * @return array
     */
    public static function getGrouped(): array
    {
        $permissions = Permission::all();

        $groups = [];

        foreach ($permissions as $permission) {
            $groupKey = explode('.', $permission->key)[0];

            if (!isset($groups[$groupKey])) {
                $groupName = ucwords(implode(' ', explode('_', $groupKey)));

                $groups[$groupKey] = [
                    'name' => $groupName,
                    'permissions' => [],
                ];
            }

            $groups[$groupKey]['permissions'][] = $permission->toArray();
        }

        return $groups;
    }
}
