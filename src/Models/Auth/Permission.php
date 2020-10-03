<?php

namespace Devel\Models\Auth;

use Devel\Models\Model;

class Permission extends Model
{
    public $table = 'devel_user_permissions';

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
        'module',
    ];

    protected $searchable = [
        'key',
        'name',
        'module',
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
                    'permissions' => collect([]),
                ];
            }

            $groups[$groupKey]['permissions'][] = $permission->toArray();
        }

        // Sort items within groups alphabetically
        foreach ($groups as &$group) {
            $group['permissions'] = $group['permissions']->sortBy('name')
                ->toArray();
        }

        return $groups;
    }
}
