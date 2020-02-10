export default class {
    constructor(object = {}) {
        this.user = object;
        this.authenticated = (Object.keys(object).length > 0);
    }

    hasPermissions(permissions = []) {
        if (typeof permissions !== 'string' && !Array.isArray(permissions)) {
            return false;
        }

        if (typeof permissions === 'string') {
            permissions = [permissions];
        }

        for (let permission of permissions) {
            if (!this.hasPermission(permission)) {
                return false;
            }
        }

        return true;
    }

    hasPermission(permission = '') {
        if (!permission) {
            return false;
        }

        return this.hasPersonalPermission(permission) || this.hasPermissionViaRole(permission);
    }

    hasPersonalPermission(permission = '') {
        return !! this.user.permissions.find(item => item.key === permission);
    }

    hasPermissionViaRole(permission = '') {
        for (let role of this.user.roles) {
            if (role.permissions.find(item => item.key === permission)) {
                return true;
            }
        }

        return false;
    }
}