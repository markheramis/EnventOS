<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    protected $table = 'orders';

    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    public function customer(){
        return $this->belongsTo('App\Models\Customer');
    }

    public function items(){
        return $this->hasMany('App\Models\OrderItems','order_id','id');
    }
}
