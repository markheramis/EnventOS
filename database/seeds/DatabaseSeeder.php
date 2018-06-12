<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        Model::unguard();
        $this->call(UsersTableSeeder::class);
        $this->call(RolesAndPermissionsSeeder::class);
        $this->call(CustomerTableSeeder::class);
        $this->call(SuppliersTableSeeder::class);
        $this->call(ItemsSeeder::class);
        $this->call(InventoriesTableSeeder::class);
        $this->call(ItemKitsSeeder::class);
        Model::reguard();
    }

}
