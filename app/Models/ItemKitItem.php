<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemKitItem extends Model
{
    protected $table = 'item_kit_items';

    public function itemKit()
    {
        return $this->hasOne('App\Models\Item','id','item_kit_id');
    }
    public function item()
    {
        return $this->hasOne('App\Models\Item','id','item_id');
    }

}
