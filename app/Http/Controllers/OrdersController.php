<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use App\Http\Requests;
use App\Models\Orders;
use App\Models\OrderItems;
use App\Models\Inventory;

/*
 * @todo must add a safe delete option
 */
class OrdersController extends Controller{

    public function getIndex(Request $request){
        $orders = Orders::with([
            'user' => function($query){
                $query->select('id','name');
            },
            'customer' => function($query){
                $query->select('id','first_name', 'last_name');
            }
        ])
        ->withCount('items')
        ->get();
        return response()->success(compact('orders'));
    }
    /**
     * @params $id the order id
     * @uses App\Models\Orders;
     */
    public function getOrder($id){
        $order = Orders::with([
            'user' => function($query){
                $query->select('id','name');
            },
            'customer' => function($query){
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
                ->join('items','order_items.item_id','=','items.id')
                ->select('order_items.*','items.item_name as name');
            }
        ])
        ->find($id);
        /*
         * add '0' (zeroes) in front of the purchase->id until it is exactly 8 characters long
         * @credits: John David Sadia Lozano @ PD
         */
        $order->or = str_pad($order->id, 8, "0", STR_PAD_LEFT);
        return response()->success($order);
    }

    /**
     * @param $request - the HTTP request data
     * @note I am not sure if this is the most efficient way to go about editing a transaction, the thing is, i made a return transaction before i update the new transaction on the inventory table.
     * in short, I've made the customer return the purchased items first before we can make the edit.
     */
    public function putOrder(Request $request)
    {
        $data = $request->input('data');
        $orders = Orders::find($data['id']);
        $orders->customer_id = $data['customer_id'];
        $orders->payment_type = $data['payment_type'];
        $orders->status = $data['status'];
        $orders->comments = $data['comments'];
        if($orders->save())
        {
            return response()->success('success');
        }
    }

    public function postOrder(Request $request)
    {
        $orders = new Orders;
        $orders->customer_id = $request->input('customer_id');
        $orders->user_id = Auth::user()->id;
        $orders->cost_price = $request->input('cost_price');
        $orders->selling_price = $request->input('selling_price');
        $orders->payment_amount = $request->input('payment_amount');
        $orders->payment_type = $request->input('payment_type');
        $orders->status = $request->input('status');
        $orders->comments = $request->input('comments');
        if($orders->save()){
            foreach($request->input('orderItems') as $item){
                $orderItems = new orderItems;
                $orderItems->order_id = $orders->id;
                $orderItems->item_id = $item['id'];
                $orderItems->cost_price = $item['cost_price'];
                $orderItems->selling_price = $item['selling_price'];
                $orderItems->quantity = $item['quantity'];
                $orderItems->total_cost = $item['total_cost_price'];
                $orderItems->total_selling = $item['total_selling_price'];
                $orderItems->save();
                $inventory = new Inventory;
                $inventory->item_id = $item['id'];
                $inventory->user_id = Auth::user()->id;
                $inventory->in_out_qty = (0 - $item['quantity']);
                $inventory->remarks = 'Deducted from order transaction';
                $inventory->order_id = $orders->id;
                $inventory->save();
            }
            return response()->success('success');
        }
    }

    public function deleteOrder($id)
    {
        try{
            $order = Orders::find($id);
            $orderItems = OrderItems::where('order_id', $order->id);
            $inventory = Inventory::where('order_id',$order->id);
            $orderItems->delete();
            $inventory->delete();
            $order->delete();
        }
        catch(\Exception $ex)
        {
            return response()->error('Failed to delete order');
        }
    }

    public function getStatusCount()
    {
        $completed = Orders::where('status','complete')->count();
        $delivering = Orders::where('status','delivering')->count();
        $processing = Orders::where('status','processing')->count();
        $cancelled = Orders::where('status','cancelled')->count();

        return response()->success([
            'complete' => $completed,
            'delivering' => $delivering,
            'processing' => $processing,
            'cancelled' => $cancelled
        ]);
    }

    public function getCount(Request $request)
    {
        $user_id = ($request->query('user_id'))? $request->query('user_id') : false;
        $customer_id = ($request->query('customer_id')) ? $request->query('customer_id') : false;

        $count = Orders::when($user_id,function($query) use($user_id) {
            $query->where('user_id',$user_id);
        })
        ->when($customer_id,function($query) use($customer_id) {
            $query->where('customer_id',$customer_id);
        })
        ->count();
        return response()->success($count);
    }


}
