<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    protected $table = 'sales';

    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    public function customer(){
        return $this->belongsTo('App\Models\Customer');
    }

    public function items(){
        return $this->hasMany('App\Models\SaleItems','sale_id','id');
    }
}
