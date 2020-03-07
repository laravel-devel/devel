<?php

namespace Modules\DevelCore\Traits;

trait HasPermissions
{
    /**
     * Determine whether the user has certain permission(s)
     *
     * @param string|array $permissions
     * @return boolean
     */
    public function hasPermissions($permissions): bool
    {
        if (is_string($permissions)) {
            // Determine whtether this is a conditional permission
            if (strpos($permissions, '||') !== false || strpos($permissions, '&&') !== false) {
                return $this->hasConditionalPermissions($permissions);
            }
            
            $permissions = [$permissions];
        }

        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission) === false) {
                return false;
            }
        }

        return true;
    }

    /**
     * Determine whether the user has a permission based on a logical expression
     *
     * @param string|array $condition
     * @return boolean
     */
    protected function hasConditionalPermissions($condition): bool
    {
        preg_match_all('/[a-z\._]*/', $condition, $matches);

        $permissions = array_values(array_filter($matches[0], function ($item) {
            return $item;
        }));
        
        foreach ($permissions as $key) {
            $condition = str_replace(
                $key,
                $this->hasPermission($key) ? 'true' : 'false',
                $condition
            );
        }

        return eval("return $condition;");
    }

    /**
     * Determine whether the user has a certain permission granted
     *
     * @param string $permission
     * @return boolean
     */
    protected function hasPermission(string $permission): bool
    {
        return $this->hasPersonalPermission($permission) === true || $this->hasPermissionViaRole($permission) === true;
    }

    /**
     * Determine whether the user has a certain permission granted personally
     *
     * @param string $permission
     * @return boolean
     */
    public function hasPersonalPermission(string $permission): bool
    {
        return $this->permissions->where('key', $permission)->first() !== null;
    }

    /**
     * Determine whether the user has a certain permission granted via a role
     *
     * @param string $permission
     * @return boolean
     */
    protected function hasPermissionViaRole(string $permission): bool
    {
        foreach ($this->roles as $role) {
            if ($role->hasPersonalPermission($permission) === true) {
                return true;
            }
        }

        return false;
    }
}
