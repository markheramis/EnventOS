<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchases extends Model
{
    protected $table = 'purchases';

    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    public function supplier(){
        return $this->belongsTo('App\Models\Supplier');
    }

    public function items(){
        return $this->hasMany('App\Models\PurchaseItems','purchase_id','id');
    }
}
