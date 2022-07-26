<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class MasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $user_admin = User::factory(
            [
                'fullname' => 'admin',
                'email' => 'admin@mail.com',
            ]
        )->create();

        $admin_role = Role::create(['guard_name' => 'api', 'name' => 'Admin']);
        $farmer_role = Role::create(['name' => 'Farmer', 'guard_name' => 'api']);
        $customer_role = Role::create(['name' => 'Customer', 'guard_name' => 'api']);

        $user_admin->assignRole($admin_role);

        Permission::create(['name' => 'show all post', 'guard_name' => 'api']);
        Permission::create(['name' => 'show post', 'guard_name' => 'api']);
        Permission::create(['name' => 'create post', 'guard_name' => 'api']);
        Permission::create(['name' => 'update post', 'guard_name' => 'api']);
        Permission::create(['name' => 'delete post', 'guard_name' => 'api']);

        Permission::create(['name' => 'show all comment', 'guard_name' => 'api']);
        Permission::create(['name' => 'show comment', 'guard_name' => 'api']);
        Permission::create(['name' => 'create comment', 'guard_name' => 'api']);
        Permission::create(['name' => 'update comment', 'guard_name' => 'api']);
        Permission::create(['name' => 'delete comment', 'guard_name' => 'api']);

        Permission::create(['name' => 'show all user', 'guard_name' => 'api']);
        Permission::create(['name' => 'show user', 'guard_name' => 'api']);
        Permission::create(['name' => 'create user', 'guard_name' => 'api']);
        Permission::create(['name' => 'update user', 'guard_name' => 'api']);
        Permission::create(['name' => 'delete user', 'guard_name' => 'api']);

        $farmer_role->syncPermissions([
            'show all post',
            'show post',
            'create post',
            'update post',
            'delete post',
            'show all comment',
            'show comment',
            'create comment',
            'update comment',
            'delete comment',
        ]);
        $customer_role->syncPermissions([
            'show all post',
            'show post',
            'show all comment',
            'show comment',
            'create comment',
            'update comment',
            'delete comment',
        ]);

        $admin_role->syncPermissions(Permission::all());
    }
}
