// resources/js/globalFunctions.js

export const hasRole = (role, roles) => {
    if (Array.isArray(role)) {
        return role.some((r) => roles.some((userRole) => userRole === r));
    }
    return roles.some((r) => r === role);
};

export const hasPermission = (permission, permissions) => {
    if (Array.isArray(permission)) {
        return permission.some((p) =>
            permissions.some((userPermission) => userPermission === p)
        );
    }
    return permissions.some((p) => p === permission);
};
