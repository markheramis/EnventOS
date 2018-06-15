<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Receivings extends Model
{
    protected $table = 'receivings';

    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    public function supplier(){
        return $this->belongsTo('App\Models\Supplier');
    }

    public function items(){
        return $this->hasMany('App\Models\ReceivingItems','receiving_id','id');
    }
}
