<?php

namespace Modules\DevelCore\Traits;

trait HasPermissions
{
    /**
     * Determine if the user has certain permission(s)
     *
     * @param string|array $permissions
     * @return boolean
     */
    public function hasPermissions($permissions): bool
    {
        if (is_string($permissions)) {
            $permissions = [$permissions];
        }

        foreach ($permissions as $permission) {
            if (!$this->hasPermission($permission)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Determine if the user has a certain permission granted
     *
     * @param string $permission
     * @return boolean
     */
    protected function hasPermission(string $permission): bool
    {
        return $this->hasPersonalPermission($permission) || $this->hasPermissionViaRole($permission);
    }

    /**
     * Determine if the user has a certain permission granted personally
     *
     * @param string $permission
     * @return boolean
     */
    public function hasPersonalPermission(string $permission): bool
    {
        return $this->permissions->where('key', $permission)->first() == true;
    }

    /**
     * Determine if the user has a certain permission granted via a role
     *
     * @param string $permission
     * @return boolean
     */
    protected function hasPermissionViaRole(string $permission): bool
    {
        foreach ($this->roles as $role) {
            if ($role->hasPersonalPermission($permission)) {
                return true;
            }
        }

        return false;
    }
}
