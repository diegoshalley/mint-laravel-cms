<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'content.view', 'content.create', 'content.edit', 'content.submit',
            'content.review', 'content.approve', 'content.publish', 'content.archive', 'content.rollback',
            'media.manage', 'media.approve', 'navigation.manage', 'redirects.manage', 'users.manage', 'audit.view',
        ];
        foreach ($permissions as $permission) Permission::findOrCreate($permission, 'web');

        $map = [
            'Super Administrator' => $permissions,
            'CMS Administrator' => $permissions,
            'Publisher' => ['content.view', 'content.review', 'content.approve', 'content.publish', 'content.archive', 'content.rollback', 'media.approve', 'audit.view'],
            'Reviewer' => ['content.view', 'content.review'],
            'Editor' => ['content.view', 'content.create', 'content.edit', 'content.submit'],
            'Media Manager' => ['content.view', 'media.manage'],
            'Auditor' => ['content.view', 'audit.view'],
        ];
        foreach ($map as $role => $grants) Role::findOrCreate($role, 'web')->syncPermissions($grants);
    }
}
