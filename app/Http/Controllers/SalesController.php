<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use App\Http\Requests;
use App\Models\Sales;
use App\Models\SaleItems;
use App\Models\Inventory;

/*
 * @todo must add a safe delete option
 */
class SalesController extends Controller{

    public function getIndex(Request $request){
        // if($user->can('view_sales')){
            $sales = Sales::with([
                'user' => function($query){
                    $query->select('id','name');
                },
                'customer' => function($query){
                    $query->select('id','first_name', 'last_name');
                }
            ])
            ->withCount('items')
            ->get();
            return response()->success(compact('sales'));
        // } else {
                // return response()->error('Permission denied');
        // }
    }

    public function getSales($id){
        // if($user->can('view_sales')){
            $sale = Sales::with([
                'user' => function($query){
                    $query->select('id','name');
                },
                'customer' => function($query){
                    $query->select('id','first_name','last_name');
                },
                'items'
            ])
            ->find($id);
            return response()->success($sale);
        // } else {
            // return response()->error('Permission denied');
        // }
    }

    /**
     * @param $request - the HTTP request data
     * @note I am not sure if this is the most efficient way to go about editing a transaction, the thing is, i made a return transaction before i update the new transaction on the inventory table.
     * in short, I've made the customer return the purchased items first before we can make the edit.
     */
    public function putSales(Request $request)
    {
        // if ($user->can('edit_sales')){
            $data = $request->input('data');
            $sales = Sales::find($data['id']);
            $sales->customer_id = $data['customer_id'];
            $sales->cost_price = $data['cost_price'];
            $sales->selling_price = $data['selling_price'];
            $sales->payment_type = $data['payment_type'];
            $sales->payment_amount = $data['payment_amount'];
            if($sales->save())
            {
                /*
                 * This process is inefficient, will change this soon to improve data-integrity
                 * @status temporary
                 * @todo must do it so that we don't actually delete old saleItem and inventory data that were not deleted in the transaction to preserve the transaction data and therefore improve data-integrity
                 */

                # return all items
                foreach($sales->items as $item){
                    $inventory = new Inventory;
                    $inventory->item_id = $item->item_id;
                    $inventory->user_id = Auth::user()->id;
                    $inventory->in_out_qty = $item->quantity;
                    $inventory->remarks = 'Item returned due to transaction edit on sales (#' . $sales->id . ')';
                    $inventory->sales_id = $sales->id;
                    $inventory->save();
                }

                SaleItems::where('sale_id',$sales->id)->delete();

                foreach($data['items'] as $item){
                    $saleItem = new saleItems;
                    $saleItem->sale_id = $sales->id;
                    $saleItem->item_id = $item['id'];
                    $saleItem->cost_price = $item['cost_price'];
                    $saleItem->selling_price = $item['selling_price'];
                    $saleItem->quantity = $item['quantity'];
                    $saleItem->total_cost = $item['total_cost_price'];
                    $saleItem->total_selling = $item['total_selling_price'];
                    $saleItem->save();

                    # make the new inventory transaction.
                    $inventory = new Inventory;
                    $inventory->item_id = $item['id'];
                    $inventory->user_id = Auth::user()->id;
                    $inventory->in_out_qty = ($item['quantity'] * -1);
                    $inventory->remarks = 'Deducted from sale transaction';
                    $inventory->sales_id = $sales->id;
                    $inventory->save();
                }
            }
            return response()->success('success');
        // }else{
        //  return response()->error('Permission denied');
        // }
    }

    public function postSales(Request $request)
    {

        // if ($user->can('create_sales')){
            $sales = new Sales;
            $sales->customer_id = $request->input('customer_id');
            $sales->user_id = Auth::user()->id;
            $sales->cost_price = $request->input('cost_price');
            $sales->selling_price = $request->input('selling_price');
            $sales->payment_amount = $request->input('payment_amount');
            $sales->payment_type = $request->input('payment_type');
            $sales->comments = $request->input('comments');
            if($sales->save()){
                foreach($request->input('saleItems') as $item){
                    $saleItems = new saleItems;
                    $saleItems->sale_id = $sales->id;
                    $saleItems->item_id = $item['id'];
                    $saleItems->cost_price = $item['cost_price'];
                    $saleItems->selling_price = $item['selling_price'];
                    $saleItems->quantity = $item['quantity'];
                    $saleItems->total_cost = $item['total_cost_price'];
                    $saleItems->total_selling = $item['total_selling_price'];
                    $saleItems->save();
                    $inventory = new Inventory;
                    $inventory->item_id = $item['id'];
                    $inventory->user_id = Auth::user()->id;
                    $inventory->in_out_qty = (0 - $item['quantity']);
                    $inventory->remarks = 'Deducted from sale transaction';
                    $inventory->sales_id = $sales->id;
                    $inventory->save();
                }
                return response()->success('success');
            }
        // } else {
                // return response()->error('Permission denied');
        // }
    }

    public function deleteSales($id){
        // if ($user->can('delete_sales')){
            $sale = Sales::find($id);
            $saleItems = SaleItems::where('sale_id', $sale->id);

            $saleItems->delete();
            $sale->delete();
        // }  else {
                // return response()->error('Permission denied');
        // }
    }

}
