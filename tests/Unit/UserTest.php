<?php

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;

test('it creates a user successfully', function () {
    $user = User::create([
        'name' => 'Juan Pérez',
        'email' => 'juan.perez@example.com',
        'password' => bcrypt('password123'),
        'fecha_nacimiento' => '1990-01-01',
        'ci' => '12345678',
        'telefono' => '789456123',
    ]);

    expect(User::where('email', 'juan.perez@example.com')->exists())->toBeTrue();
    expect($user->name)->toBe('Juan Pérez');
    expect($user->email)->toBe('juan.perez@example.com');
});

test('it updates a user successfully', function () {
    $user = User::create([
        'name' => 'Juan Pérez',
        'email' => 'juan.perez@example.com',
        'password' => bcrypt('password123'),
    ]);

    $user->update([
        'name' => 'Juan Actualizado',
        'email' => 'juan.actualizado@example.com',
    ]);

    $user->refresh();
    expect($user->name)->toBe('Juan Actualizado');
    expect($user->email)->toBe('juan.actualizado@example.com');
});

test('it deletes a user successfully', function () {
    $user = User::create([
        'name' => 'Juan Pérez',
        'email' => 'juan.perez@example.com',
        'password' => bcrypt('password123'),
    ]);

    $user->delete();

    expect(User::where('email', 'juan.perez@example.com')->exists())->toBeFalse();
});

test('it assigns roles to a user', function () {
    $user = User::create([
        'name' => 'Juan Pérez',
        'email' => 'juan.perez@example.com',
        'password' => bcrypt('password123'),
    ]);

    $role = Role::create(['name' => 'admin']);
    $user->assignRole('admin');

    expect($user->hasRole('admin'))->toBeTrue();
});

test('it assigns permissions to a user', function () {
    $user = User::create([
        'name' => 'Juan Pérez',
        'email' => 'juan.perez@example.com',
        'password' => bcrypt('password123'),
    ]);

    $permission = Permission::create(['name' => 'edit articles']);
    $user->givePermissionTo('edit articles');

    expect($user->can('edit articles'))->toBeTrue();
});

