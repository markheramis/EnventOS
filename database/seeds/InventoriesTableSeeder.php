<?php

use Illuminate\Database\Seeder;

use App\Models\Inventory;

class InventoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*
        $this->create([
            'item_id' => 1,
            'user_id' => 1,
            'in_out_qty' => 10
        ]);
        $this->create([
            'item_id' => 1,
            'user_id' => 1,
            'in_out_qty' => 10
        ]);
        $this->create([
            'item_id' => 1,
            'user_id' => 1,
            'in_out_qty' => 5
        ]);
        $this->create([
            'item_id' => 1,
            'user_id' => 1,
            'in_out_qty' => 5
        ]);
        $this->create([
            'item_id' => 2,
            'user_id' => 1,
            'in_out_qty' => 5
        ]);
        $this->create([
            'item_id' => 2,
            'user_id' => 1,
            'in_out_qty' => 5
        ]);
        $this->create([
            'item_id' => 3,
            'user_id' => 1,
            'in_out_qty' => 3
        ]);
        $this->create([
            'item_id' => 3,
            'user_id' => 1,
            'in_out_qty' => 4
        ]);
        */
        $this->create([
            'item_id' => 4,
            'user_id' => 1,
            'in_out_qty' =>1
        ]);

        $this->create([
            'item_id' => 5,
            'user_id' => 1,
            'in_out_qty' => 15
        ]);

    }

    public function create($args)
    {
        $inventory = new Inventory;
        $inventory->item_id = $args['item_id'];
        $inventory->user_id = $args['user_id'];
        $inventory->in_out_qty = $args['in_out_qty'];
        $inventory->remarks = 'Added from seeder';

        $inventory->save();
    }
}
