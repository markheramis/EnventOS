<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use App\Http\Requests;
use App\Models\Purchases;
use App\Models\PurchaseItems;
use App\Models\Inventory;

use App\Http\Requests\purchase\createRequest;
use App\Http\Requests\purchase\deleteRequest;
use App\Http\Requests\purchase\updateRequest;
use App\Http\Requests\purchase\viewRequest;

/*
 * @todo must add a safe delete option
 */
class PurchasesController extends Controller
{
    /**
     * @param App\Http\Requests\purchase\viewRequest $reqiest
     * @uses App\Models\Purchases
     */
    public function getIndex(viewRequest $request)
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
    /**
     * @param App\Http\Requests\purchase\viewRequest $reqiest
     * @param int $id
     * @uses App\Models\Purchases
     * @uses str_pad
     */
    public function getPurchase(viewRequest $request, $id)
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
            'products' => function($query){
                $query
                ->join('products','purchase_products.product_id','=','products.id')
                ->select('purchase_products.*','products.product_name as name');
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
    /**
     * @param App\Http\Requests\purchase\updateRequest $reqiest
     * @uses App\Models\Purchases
     */
    public function putPurchase(updateRequest $request)
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
    /**
     * @param App\Http\Requests\purchase\createRequest $reqiest
     * @uses App\Models\Purchases
     * @uses App\Models\PurchaseItems
     * @uses App\Models\Inventory
     */
    public function postPurchase(createRequest $request)
    {
        $purchase = new Purchases;
        $purchase->user_id = Auth::user()->id;
        $purchase->supplier_id = $request->input('supplier_id');
        $purchase->payment_type = $request->input('payment_type');
        $purchase->cost_price = $request->input('cost_price');
        $purchase->comments = $request->input('comments');

        if($purchase->save()){

            foreach($request->input('purchaseItems') as $product){
                $purchaseItem = new PurchaseItems;
                $purchaseItem->purchase_id = $purchase->id;
                $purchaseItem->product_id = $product['id'];
                $purchaseItem->cost_price = $product['cost_price'];
                $purchaseItem->quantity = $product['quantity'];
                $purchaseItem->total_cost = $product['total_cost_price'];
                $purchaseItem->save();

                $inventory = new Inventory;
                $inventory->product_id = $product['id'];
                $inventory->user_id = Auth::user()->id;
                $inventory->in_out_qty = $product['quantity'];
                $inventory->remarks = 'Added from purchase transaction (#' . $purchase->id . ')';
                $inventory->purchase_id = $purchase->id;
                $inventory->save();
            }
            return response()->success('successs');
        }
    }
    /**
     * @param App\Http\Requests\purchase\deleteRequest $reqiest
     * @uses App\Models\Purchases
     * @uses App\Models\PurchaseItems
     * @uses App\Models\Inventory
     */
    public function deletePurchase(deleteRequest $request, $id)
    {
        $purchase = Purchases::find($id);
        $purchaseItems = PurchaseItems::where('purchase_id',$purchase->id);
        $inventory = Inventory::where('purchase_id',$purchase->id);
        $inventory->delete();
        $purchaseItems->delete();
        $purchase->delete();
    }
    /**
     * @param App\Http\Requests\purchase\viewRequest $reqiest
     * @uses App\Models\Purchases
     */
    public function getCount(viewRequest $request)
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
