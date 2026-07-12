<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $adminRole = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        $adminRole->givePermissionTo(
            collect(['roles.manage', 'permissions.manage'])
                ->map(fn (string $name) => Permission::firstOrCreate([
                    'name' => $name,
                    'guard_name' => 'web',
                ]))
        );

        $adminUser = User::query()->oldest('id')->first();

        if (! $adminUser) {
            $adminUser = User::create([
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]);
        }

        if (! $adminUser->hasRole('admin')) {
            $adminUser->assignRole($adminRole);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        Role::where('name', 'admin')->delete();
        Permission::whereIn('name', ['roles.manage', 'permissions.manage'])->delete();
    }
};
