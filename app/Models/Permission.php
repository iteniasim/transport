<?php

namespace App\Models;

use Spatie\Permission\Models\Permission as SpatiePermission;

/**
 * @mixin IdeHelperPermission
 */
class Permission extends SpatiePermission
{
    /**
     * Returns the default permissions as an array of strings.
     */
    public static function defaultPermissions(): array
    {
        return [
            'view_users',
            'show_users',
            'create_users',
            'update_users',
            'delete_users',
            'view_roles',
            'show_roles',
            'create_roles',
            'update_roles',
            'delete_roles',
            'view_tasks',
            'show_tasks',
            'create_tasks',
            'update_tasks',
            'delete_tasks',
        ];
    }
}
