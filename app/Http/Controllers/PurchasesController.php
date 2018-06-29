<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use App\Http\Requests;
use App\Models\Purchases;
use App\Models\PurchaseItems;
use App\Models\Inventory;
/*
 * @todo must add a safe delete option
 */
class PurchasesController extends Controller
{
    public function getIndex()
    {
        $purchases = Purchases::with([
            'user' => function($query){
                $query->select('id','name');
            },
            'supplier' => function($query){
                $query->select('id','first_name','last_name');
            }
        ])
        ->withCount('items')
        ->get();
        return response()->success(compact('purchases'));
    }

    public function getPurchase($id)
    {
        $purchase = Purchases::with([
            'user' => function($query){
                $query->select('id','name');
            },
            'supplier' => function($query){
                $query->select('id','first_name','last_name');
            },
            'items'
        ])
        ->find($id);
        return response()->success($purchase);
    }

    public function putPurchase(Request $request)
    {
        $data = $request->input('data');
        $purchase = Purchases::find($data['id']);
        $purchase->supplier_id = $data['supplier_id'];
        $purchase->cost_price = $data['cost_price'];
        $purchase->selling_price = $data['selling_price'];
        $purchase->payment_type = $data['payment_type'];
        $purchase->amount_tendered = $data['amount_tendered'];
        if($purchase->save())
        {
            PurchaseItems::where('purchase_id', $purchase->id)->delete();
            Inventory::where('purchase_id',$purchase->id)->delete();

            foreach($data['items'] as $item)
            {
                $purchaseItem = new PurchaseItems;
                $purchaseItem->purchase_id = $purchase->id;
                $purchaseItem->item_id = $item['id'];
                $purchaseItem->cost_price = $item['cost_price'];
                $purchaseItem->selling_price = $item['selling_price'];
                $purchaseItem->quantity = $item['quantity'];
                $purchaseItem->save();

                $inventory = new Inventory;
                $inventory->item_id = $item['id'];
                $inventory->user_id = Auth::user()->id;
                $inventory->in_out_qty = $item['quantity'];
                $inventory->remarks = 'Added from purchase transaction';
                $inventory->purchase_id = $purchase->id;
                $inventory->save();
            }

            return response()->success('success');
        }
    }

    public function postPurchase(Request $request)
    {
        $purchase = new Purchases;
        $purchase->user_id = Auth::user()->id;
        $purchase->supplier_id = $request->input('supplier_id');
        $purchase->payment_type = $request->input('payment_type');
        $purchase->amount_tendered = $request->input('amount_tendered');
        $purchase->comments = $request->input('comments');

        if($purchase->save()){

            foreach($request->input('purchaseItems') as $item){
                $purchaseItem = new PurchaseItems;
                $purchaseItem->purchase_id = $purchase->id;
                $purchaseItem->item_id = $item['id'];
                $purchaseItem->cost_price = $item['cost_price'];
                $purchaseItem->quantity = $item['quantity'];
                $purchaseItem->total_cost = $item['total_cost_price'];
                $purchaseItem->save();

                $inventory = new Inventory;
                $inventory->item_id = $item['id'];
                $inventory->user_id = Auth::user()->id;
                $inventory->in_out_qty = $item['quantity'];
                $inventory->remarks = 'Added from purchase transaction (#' . $purchase->id . ')';
                $inventory->purchase_id = $purchase->id;
                $inventory->save();
            }
            return response()->success('successs');
        }
    }

    public function deletePurchase($id)
    {
        $purchase = Purchases::find($id);
        $purchaseItems = PurchaseItems::where('purchase_id',$purchase->id);
        $purchaseItems->delete();
        $purchase->delete();
    }

    public function getCount(Request $request)
    {
        $user_id = ($request->query('user_id')) ? $request->query('user_id') : false;
        $supplier_id = ($request->query('supplier_id')) ? $request->query('supplier_id') : false;

        $count = Purchases::when($user_id, function($query) use ($user_id) {
            $query->where('user_id',$user_id);
        })
        ->when($supplier_id, function($query) use ($supplier_id) {
            $query->where('supplier_id',$supplier_id);
        })
        ->count();
        return response()->success($count);
    }
}
