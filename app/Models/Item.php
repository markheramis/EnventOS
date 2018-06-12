<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $table = 'items';

    public function kitItems(){
        return $this->hasMany('App\Models\ItemKitItem','item_kit_id','id');
    }

    public function inventory(){
        return $this->hasMany('App\Models\Inventory','item_id','id');
    }
}
