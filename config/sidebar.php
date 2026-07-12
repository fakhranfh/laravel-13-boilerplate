<?php

return [
    [
        'label' => 'Dashboard',
        'route' => 'dashboard',
        'icon' => 'dashboard',
        'active_pattern' => 'dashboard',
    ],
    [
        'label' => 'Users',
        'route' => 'users.index',
        'icon' => 'group',
        'active_pattern' => 'users.*',
        'requires_role' => 'admin',
    ],
    [
        'label' => 'Roles',
        'route' => 'roles.index',
        'icon' => 'shield',
        'active_pattern' => 'roles.*',
        'requires_role' => 'admin',
    ],
    [
        'label' => 'Permissions',
        'route' => 'permissions.index',
        'icon' => 'key',
        'active_pattern' => 'permissions.*',
        'requires_role' => 'admin',
    ],
];
