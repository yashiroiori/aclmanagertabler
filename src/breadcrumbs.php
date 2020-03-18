<?php

use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;

// Roles
Breadcrumbs::for('role.index', function ($trail) {
    $trail->push('Roles', route('role.index'));
});

// Roles > Upload Role
Breadcrumbs::for('role.create', function ($trail) {
    $trail->parent('role.index');
    $trail->push('Nuevo Rol', route('role.create'));
});

// Roles > [Role Name]
Breadcrumbs::for('role.show', function ($trail, $role) {
    $trail->parent('role.index');
    $trail->push($role->name, route('role.show', $role->id));
});

// Roles > [Role Name] > Edit Role
Breadcrumbs::for('role.edit', function ($trail, $role) {
    $trail->parent('role.show', $role);
    $trail->push('Editar Rol', route('role.edit', $role->id));
});

// Users
Breadcrumbs::for('user.index', function ($trail) {
    $trail->push('Usuarios', route('user.index'));
});

// Users > Upload Role
Breadcrumbs::for('user.create', function ($trail) {
    $trail->parent('user.index');
    $trail->push('Nuevo Usuario', route('user.create'));
});

// Users > [Role Name]
Breadcrumbs::for('user.show', function ($trail, $user) {
    $trail->parent('user.index');
    $trail->push($user->name, route('user.show', $user->id));
});

// Users > [Role Name] > Edit Role
Breadcrumbs::for('user.edit', function ($trail, $user) {
    $trail->parent('user.show', $user);
    $trail->push('Editar Usuario', route('user.edit', $user->id));
});