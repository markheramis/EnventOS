<?php

use Illuminate\Database\Seeder;
use app\Models\Product;
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
        $items = Product::select('id')->get();
        foreach($items as $item){
            $this->create([
                'product_id' => $item->id,
                'user_id' => 1,
                'in_out_qty' => rand(1,10),
            ]);
        }

    }

    public function create($args){
        $inventory = new Inventory;
        $inventory->product_id = $args['product_id'];
        $inventory->user_id = $args['user_id'];
        $inventory->in_out_qty = $args['in_out_qty'];
        $inventory->remarks = 'Created from seeder';
        $inventory->save();
    }
}
