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
        ## USER PERMISSIONS
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
        ## ROLE PERMISSIONS
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
        # PERMISSION PERMISSIONS
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
        ## SUPPLIER PERMISSIONS
        $this->permissions['supplier']['create'] = Permission::create([
            'name' => 'Create supplier',
            'slug' => 'create.supplier',
            'description' => 'Can create supplier',
        ]);
        $this->permissions['supplier']['update'] = Permission::create([
            'name' => 'Update supplier',
            'slug' => 'update.supplier',
            'description' => 'Can update supplier',
        ]);
        $this->permissions['supplier']['delete'] = Permission::create([
            'name' => 'Delete supplier',
            'slug' => 'delete.supplier',
            'description' => 'Can delete supplier',
        ]);
        $this->permissions['supplier']['view'] = Permission::create([
            'name' => 'View supplier',
            'slug' => 'view.supplier',
            'description' => 'Can view supplier',
        ]);
        ## CUSTOMER PERMISSIONS
        $this->permissions['customer']['create'] = Permission::create([
            'name' => 'Create customer',
            'slug' => 'create.customer',
            'description' => 'Can create customer',
        ]);
        $this->permissions['customer']['update'] = Permission::create([
            'name' => 'Update customer',
            'slug' => 'update.customer',
            'description' => 'Can update customer',
        ]);
        $this->permissions['customer']['delete'] = Permission::create([
            'name' => 'Delete customer',
            'slug' => 'delete.customer',
            'description' => 'Can delete customer',
        ]);
        $this->permissions['customer']['view'] = Permission::create([
            'name' => 'View customer',
            'slug' => 'view.customer',
            'description' => 'Can view customer',
        ]);
        ## PRODUCT PERMISSIONS
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
        ## SALES PERMISSIONS
        $this->permissions['order']['create'] = Permission::create([
            'name' => 'Create order',
            'slug' => 'create.order',
            'description' => 'Can create order',
        ]);
        $this->permissions['order']['update'] = Permission::create([
            'name' => 'Update order',
            'slug' => 'update.order',
            'description' => 'Can update order',
        ]);
        $this->permissions['order']['delete'] = Permission::create([
            'name' => 'Delete order',
            'slug' => 'delete.order',
            'description' => 'Can delete order',
        ]);
        $this->permissions['order']['view'] = Permission::create([
            'name' => 'View order',
            'slug' => 'view.order',
            'description' => 'Can view order',
        ]);
        ## PURCHASE PERMISSIONS
        $this->permissions['purchase']['create'] = Permission::create([
            'name' => 'Create purchase',
            'slug' => 'create.purchase',
            'description' => 'Can create purchase',
        ]);
        $this->permissions['purchase']['update'] = Permission::create([
            'name' => 'Update purchase',
            'slug' => 'update.purchase',
            'description' => 'Can update purchase',
        ]);
        $this->permissions['purchase']['delete'] = Permission::create([
            'name' => 'Delete purchase',
            'slug' => 'delete.purchase',
            'description' => 'Can delete purchase',
        ]);
        $this->permissions['purchase']['view'] = Permission::create([
            'name' => 'View purchase',
            'slug' => 'view.purchase',
            'description' => 'Can view purchase',
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

        $this->roles['admin.super']->attachPermission($this->permissions['customer']['create']);
        $this->roles['admin.super']->attachPermission($this->permissions['customer']['update']);
        $this->roles['admin.super']->attachPermission($this->permissions['customer']['delete']);
        $this->roles['admin.super']->attachPermission($this->permissions['customer']['view']);

        $this->roles['admin.super']->attachPermission($this->permissions['supplier']['create']);
        $this->roles['admin.super']->attachPermission($this->permissions['supplier']['update']);
        $this->roles['admin.super']->attachPermission($this->permissions['supplier']['delete']);
        $this->roles['admin.super']->attachPermission($this->permissions['supplier']['view']);
    }

    private function attachRolesToUsers() {
        $user = User::find(1);
        $user->attachRole($this->roles['admin.super']);
    }

}
