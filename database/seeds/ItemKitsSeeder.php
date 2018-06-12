<?php

use Illuminate\Database\Seeder;
use App\Models\ItemKitItem;

class ItemKitsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->create([
            'item_kit_id' => 7,
            'item_id' => 2,
            'quantity' => 1,
            'total_cost_price' => 35000.00,
            'total_selling_price' => 38500.00
        ]);
        $this->create([
            'item_kit_id' => 7,
            'item_id' => 5,
            'quantity' => 1,
            'total_cost_price' => 955.00,
            'total_selling_price' => 1050.99
        ]);
        $this->create([
            'item_kit_id' => 7,
            'item_id' => 6,
            'quantity' => 1,
            'total_cost_price' => 450.00,
            'total_selling_price' => 599.99
        ]);
    }

    public function create($args)
    {
        $itemKitItem = new ItemKitItem;
        $itemKitItem->item_kit_id = $args['item_kit_id'];
        $itemKitItem->item_id = $args['item_id'];
        $itemKitItem->quantity = $args['quantity'];
        $itemKitItem->total_cost_price = $args['total_cost_price'];
        $itemKitItem->total_selling_price = $args['total_selling_price'];
        $itemKitItem->save();

    }
}
