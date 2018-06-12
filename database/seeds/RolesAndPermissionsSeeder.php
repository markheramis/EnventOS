<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use Bican\Roles\Models\Permission;
use Bican\Roles\Models\Role;

class RolesAndPermissionsSeeder extends Seeder {

    private $roles = [];
    private $permissions = [];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $this->createRoles();
        $this->createPermissions();
        $this->attachPermissionsToRoles();
        $this->attachRolesToUsers();
    }

    private function createRoles() {
        $this->roles['admin.super'] = Role::create([
            'name' => 'Super Admin',
            'slug' => 'admin.super',
            'description' => 'Has the highest access', // optional
            'level' => 1, // optional, set to 1 by default
        ]);
        $this->roles['staff.cashier'] = Role::create([
            'name' => 'Cashier Staff',
            'slug' => 'staff.cashier',
            'description' => 'Can access POS and Sales Data',
            'level' => 2,
        ]);
        $this->roles['staff.inventory'] = Role::create([
            'name' => 'Inventory Staff',
            'slug' => 'staff.inventory',
            'description' => 'Can access inventory systems',
            'level' => 3,
        ]);
    }

    private function createPermissions() {
        $this->permissions['user']['create'] = Permission::create([
            'name' => 'Create User',
            'slug' => 'create.user',
            'description' => 'Can create users',
        ]);
        $this->permissions['user']['update'] = Permission::create([
            'name' => 'Update User',
            'slug' => 'update.user',
            'description' => 'Can update users',
        ]);
        $this->permissions['user']['delete'] = Permission::create([
            'name' => 'Delete User',
            'slug' => 'delete.user',
            'description' => 'Can delete users',
        ]);
        $this->permissions['user']['view'] = Permission::create([
            'name' => 'View User',
            'slug' => 'view.user',
            'description' => 'Can view users',
        ]);

        $this->permissions['roles']['create'] = Permission::create([
            'name' => 'Create Role',
            'slug' => 'create.role',
            'description' => 'Can create Roles',
        ]);
        $this->permissions['roles']['update'] = Permission::create([
            'name' => 'Update Role',
            'slug' => 'update.role',
            'description' => 'Can update Roles',
        ]);
        $this->permissions['roles']['delete'] = Permission::create([
            'name' => 'Delete Role',
            'slug' => 'delete.role',
            'description' => 'Can delete Roles',
        ]);
        $this->permissions['roles']['view'] = Permission::create([
            'name' => 'View Role',
            'slug' => 'view.role',
            'description' => 'Can view Roles',
        ]);
        $this->permissions['permission']['create'] = Permission::create([
            'name' => 'Create Permission',
            'slug' => 'create.permission',
            'description' => 'Can create Permissions',
        ]);
        $this->permissions['permission']['update'] = Permission::create([
            'name' => 'Update Permission',
            'slug' => 'update.permission',
            'description' => 'Can update permission',
        ]);
        $this->permissions['permission']['delete'] = Permission::create([
            'name' => 'Delete Permission',
            'slug' => 'delete.permission',
            'description' => 'Can delete permissions',
        ]);
        $this->permissions['permission']['view'] = Permission::create([
            'name' => 'View Permission',
            'slug' => 'view.permission',
            'description' => 'Can view permissions',
        ]);
        $this->permissions['product']['create'] = Permission::create([
            'name' => 'Create products',
            'slug' => 'create.product',
            'description' => 'Can create product',
        ]);
        $this->permissions['product']['update'] = Permission::create([
            'name' => 'Update products',
            'slug' => 'update.product',
            'description' => 'Can update product',
        ]);
        $this->permissions['product']['delete'] = Permission::create([
            'name' => 'Delete products',
            'slug' => 'delete.product',
            'description' => 'Can delete products',
        ]);
        $this->permissions['product']['view'] = Permission::create([
            'name' => 'View products',
            'slug' => 'view.products',
            'description' => 'Can view products',
        ]);
    }

    private function attachPermissionsToRoles() {
        # Attach Permissions to Super Admin
        $this->roles['admin.super']->attachPermission($this->permissions['user']['create']);
        $this->roles['admin.super']->attachPermission($this->permissions['user']['update']);
        $this->roles['admin.super']->attachPermission($this->permissions['user']['delete']);
        $this->roles['admin.super']->attachPermission($this->permissions['user']['view']);

        $this->roles['admin.super']->attachPermission($this->permissions['roles']['create']);
        $this->roles['admin.super']->attachPermission($this->permissions['roles']['update']);
        $this->roles['admin.super']->attachPermission($this->permissions['roles']['delete']);
        $this->roles['admin.super']->attachPermission($this->permissions['roles']['view']);

        $this->roles['admin.super']->attachPermission($this->permissions['permission']['create']);
        $this->roles['admin.super']->attachPermission($this->permissions['permission']['update']);
        $this->roles['admin.super']->attachPermission($this->permissions['permission']['delete']);
        $this->roles['admin.super']->attachPermission($this->permissions['permission']['view']);

        $this->roles['admin.super']->attachPermission($this->permissions['product']['create']);
        $this->roles['admin.super']->attachPermission($this->permissions['product']['update']);
        $this->roles['admin.super']->attachPermission($this->permissions['product']['delete']);
        $this->roles['admin.super']->attachPermission($this->permissions['product']['view']);
    }

    private function attachRolesToUsers() {
        $user = User::find(1);
        $user->attachRole($this->roles['admin.super']);
    }

}
