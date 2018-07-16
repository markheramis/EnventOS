<?php

use Illuminate\Database\Seeder;
use app\Models\Item;
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
        $items = Item::select('id')->get();
        foreach($items as $item){
            $this->create([
                'item_id' => $item->id,
                'user_id' => 1,
                'in_out_qty' => rand(1,10),
            ]);
        }

    }

    public function create($args){
        $inventory = new Inventory;
        $inventory->item_id = $args['item_id'];
        $inventory->user_id = $args['user_id'];
        $inventory->in_out_qty = $args['in_out_qty'];
        $inventory->remarks = 'Created from seeder';
        $inventory->save();
    }
}
