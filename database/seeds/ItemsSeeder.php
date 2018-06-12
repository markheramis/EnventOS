<?php

use Illuminate\Database\Seeder;
use App\Models\Item;
class ItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->create([
            'item_code' => '13aset2swwer24',
            'item_name' => 'Lenovo ideapad 310',
            'size' => '11x8',
            'description' => 'Some description',
            'cost_price' => 25000.00,
            'selling_price' => 27000.00,
            'type' => 1
        ]);
        $this->create([
            'item_code' => 'jert63ert3ewsfsa',
            'item_name' => 'Lenovo thinkpad G30',
            'size' => '11x8',
            'description' => 'Some description',
            'cost_price' => 35000.00,
            'selling_price' => 38500.00,
            'type' => 1
        ]);
        $this->create([
            'item_code' => 'fksjhwiywr29sd',
            'item_name' => 'Acer aspire 211',
            'size' => '12x8.5',
            'description' => 'Some description',
            'cost_price' => 65000.00,
            'selling_price' => 73000.00,
            'type' => 1
        ]);
        $this->create([
            'item_code' => '13aset2swwer24',
            'item_name' => 'Lenovo L series GX9',
            'size' => '11x8',
            'description' => 'Some description',
            'cost_price' => 90000.00,
            'selling_price' => 107000.00,
            'type' => 1
        ]);
        $this->create([
            'item_code' => '533543534523424',
            'item_name' => 'Beat 49',
            'size' => '2x2',
            'description' => 'Some description',
            'cost_price' => 955.00,
            'selling_price' => 1050.99,
            'type' => 1
        ]);
        $this->create([
            'item_code' => '4564534645634536',
            'item_name' => 'Lenovo Black leather bag',
            'size' => '11x9',
            'description' => 'Leather water resistant bag for Lenovo',
            'cost_price' => 450.00,
            'selling_price' => 599.99,
            'type' => 1
        ]);

        $this->create([
            'item_code' => 'zjas8324sd24aaq',
            'item_name' => 'Lenovo Thinkpad G30 promo 2017',
            'size' => '11x8',
            'description' => 'Some description',
            'cost_price' => 36405.00,
            'selling_price' => 40000,
            'type' => 2
        ]);
    }

    public function create($arg)
    {
        $item = new Item;
        $item->item_code = $arg['item_code'];
        $item->item_name = $arg['item_name'];
        $item->size = $arg['size'];
        $item->description = $arg['description'];
        $item->cost_price = $arg['cost_price'];
        $item->selling_price = $arg['selling_price'];
        $item->type = $arg['type'];

        return $item->save();
    }
}
