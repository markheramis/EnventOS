<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use App\Http\Requests;
use App\Models\Receivings;
use App\Models\ReceivingItems;
use App\Models\Inventory;
/*
 * @todo must add a safe delete option
 */
class ReceivingsController extends Controller
{
    public function getIndex()
    {
        $receivings = Receivings::with([
            'user' => function($query){
                $query->select('id','name');
            },
            'supplier' => function($query){
                $query->select('id','first_name','last_name');
            }
        ])
        ->withCount('items')
        ->get();
        return response()->success(compact('receivings'));
    }

    public function getReceiving($id)
    {
        $receiving = Receivings::with([
            'user' => function($query){
                $query->select('id','name');
            },
            'supplier' => function($query){
                $query->select('id','first_name','last_name');
            },
            'items'
        ])
        ->find($id);
        return response()->success($receiving);
    }

    public function putReceiving(Request $request)
    {
        $data = $request->input('data');
        $receiving = Receivings::find($data['id']);
        $receiving->supplier_id = $data['supplier_id'];
        $receiving->cost_price = $data['cost_price'];
        $receiving->selling_price = $data['selling_price'];
        $receiving->payment_type = $data['payment_type'];
        $receiving->amount_tendered = $data['amount_tendered'];
        if($receiving->save())
        {
            ReceivingItems::where('receiving_id', $receiving->id)->delete();
            Inventory::where('receiving_id',$receiving->id)->delete();

            foreach($data['items'] as $item)
            {
                $receivingItem = new ReceivingItems;
                $receivingItem->receiving_id = $receiving->id;
                $receivingItem->item_id = $item['id'];
                $receivingItem->cost_price = $item['cost_price'];
                $receivingItem->selling_price = $item['selling_price'];
                $receivingItem->quantity = $item['quantity'];
                $receivingItem->save();

                $inventory = new Inventory;
                $inventory->item_id = $item['id'];
                $inventory->user_id = Auth::user()->id;
                $inventory->in_out_qty = $item['quantity'];
                $inventory->remarks = 'Added from receiving transaction';
                $inventory->receiving_id = $receiving->id;
                $inventory->save();
            }

            return response()->success('success');
        }
    }

    public function postReceiving(Request $request)
    {
        $receiving = new Receivings;
        $receiving->user_id = Auth::user()->id;
        $receiving->supplier_id = $request->input('supplier_id');
        $receiving->payment_type = $request->input('payment_type');
        $receiving->amount_tendered = $request->input('amount_tendered');
        $receiving->comments = $request->input('comments');

        if($receiving->save()){

            foreach($request->input('receivingItems') as $item){
                $receivingItem = new ReceivingItems;
                $receivingItem->receiving_id = $receiving->id;
                $receivingItem->item_id = $item['id'];
                $receivingItem->cost_price = $item['cost_price'];
                $receivingItem->quantity = $item['quantity'];
                $receivingItem->total_cost = $item['total_cost_price'];
                $receivingItem->save();

                $inventory = new Inventory;
                $inventory->item_id = $item['id'];
                $inventory->user_id = Auth::user()->id;
                $inventory->in_out_qty = $item['quantity'];
                $inventory->remarks = 'Added from receiving transaction (#' . $receiving->id . ')';
                $inventory->receiving_id = $receiving->id;
                $inventory->save();
            }

            return response()->success('successs');
        }
    }

    public function deleteReceiving($id)
    {
        // if ($user->can('delete_receivings')) {
            $receiving = Receivings::find($id);
            $receivingItems = ReceivingItems::where('receiving_id',$receiving->id);
            $receivingItems->delete();
            $receiving->delete();
        // } else {

        // }

    }
}
