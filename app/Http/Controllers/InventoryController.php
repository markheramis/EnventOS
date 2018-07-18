<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DateTime;
use DateInterval;
use App\Http\Requests;
use App\Models\Inventory;

use App\Http\Requests\inventoryGetAllRequest;

class InventoryController extends Controller
{
    public function getIndex(inventoryGetAllRequest $request)
    {
        $limit = ($request->query('limit'))?$request->query('limit'): 10;
        $inventory = Inventory::limit($limit)
        # optional query, execute only when orders = true
        ->when($request->query('orders'),function($query){
            # query rows that has query_id value.
            return $query->whereNotNull('order_id');
        })
        # optional query, execute only when purchases = true
        ->when($request->query('purchases'), function($query){
            # query rows that has purchase_id value.
            return $query->whereNotNull('purchase_id');
        })
        ->with([
            'item',
            'user' => function($query){
                $query->select('id','name');
            }
        ])
        ->get();
        return response()->success(compact('inventory'));
    }

    public function getRecap(Request $request){
        $recap = [];
        $current_date = new DateTime(); # this should be now
        $interval = new DateInterval('P1M');
        for($i=0; $i < 7; $i++){
            $current_recap = [];
            $end_date = $current_date->format('Y-m-d');
            $current_recap['date']['end']['month'] = $current_date->format('F');
            $current_recap['date']['end']['date'] = $current_date->format('d M, Y');
            $current_date->sub($interval);
            $start_date = $current_date->format('Y-m-d');
            $current_recap['date']['start']['month'] = $current_date->format('F');
            $current_recap['date']['start']['date'] = $current_date->format('d M, Y');
            $inventory = Inventory::whereNotNull('order_id')
            ->whereDate('created_at' , '>'  , $start_date)
            ->whereDate('created_at' , '<=' , $end_date)
            ->whereNotNull('order_id')
            ->with(['item' => function($query){
                $query->select('id','cost_price','selling_price');
            }])
            ->get();
            $current_recap['inventory'] = $inventory;
            $total_cost     = 0;
            $total_selling  = 0;
            $total_profit   = 0;
            foreach($inventory as $record){
                $qty = $record->in_out_qty * -1;
                $total_cost += $qty * $record->item['cost_price'];
                $total_selling += $qty * $record->item['selling_price'];
            }

            $total_profit = $total_selling - $total_cost;

            $current_recap['total_cost']    = $total_cost;
            $current_recap['total_selling'] = $total_selling;
            $current_recap['total_profit']  = $total_profit;
            $recap[] = $current_recap;

        }
        $recap = array_reverse($recap);
        return response()->success(compact('recap'));
    }
}
