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
                $query->select(
                    'id',
                    'first_name',
                    'last_name',
                    'email',
                    'phone',
                    'address',
                    'city',
                    'state',
                    'zip',
                    'country',
                    'company_name'
                );
            },
            'items' => function($query){
                $query
                ->join('items','purchase_items.item_id','=','items.id')
                ->select('purchase_items.*','items.item_name as name');
            }
        ])
        ->find($id);
        /*
         * add '0' (zeroes) in front of the purchase->id until it is exactly 8 characters long
         * @credits: John David Sadia Lozano @ PD
         */
        $purchase->pur = str_pad($purchase->id, 8, "0", STR_PAD_LEFT);
        return response()->success($purchase);
    }

    public function putPurchase(Request $request)
    {
        $data = $request->input('data');
        $purchase = Purchases::find($data['id']);
        $purchase->supplier_id = $data['supplier_id'];
        $purchase->payment_type = $data['payment_type'];
        $purchase->comments = $data['comments'];
        if($purchase->save())
        {
            return response()->success('success');
        }
    }

    public function postPurchase(Request $request)
    {
        $purchase = new Purchases;
        $purchase->user_id = Auth::user()->id;
        $purchase->supplier_id = $request->input('supplier_id');
        $purchase->payment_type = $request->input('payment_type');
        $purchase->cost_price = $request->input('cost_price');
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
        $inventory = Inventory::where('purchase_id',$purchase->id);
        $inventory->delete();
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
