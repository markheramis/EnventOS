<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use App\Http\Requests;
use App\Models\Orders;
use App\Models\OrderItems;
use App\Models\Inventory;

use App\Http\Requests\order\createRequest;
use App\Http\Requests\order\deleteRequest;
use App\Http\Requests\order\updateRequest;
use App\Http\Requests\order\viewRequest;
/*
 * @todo must add a safe delete option
 */
class OrdersController extends Controller{
    /**
     * @param App\Http\Request\order\viewRequest $request
     * @uses App\Models\Orders
     */
    public function getIndex(viewRequest $request){
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
     * @param App\Http\Request\order\viewRequest $request
     * @param int $id the order transacction's id.
     * @uses App\Models\Orders;
     */
    public function getOrder(viewRequest $request, $id){
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
                ->join('products','order_items.product_id','=','products.id')
                ->select('order_items.*','products.product_name as name');
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
     * @param App\Http\Request\order\updateRequest $request - the HTTP request data
     * @uses App\Models\Orders
     */
    public function putOrder(updateRequest $request)
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
    /**
     * @param App\Http\Requests\order\createRequest $request
     * @uses App\Models\Orders
     * @uses App\Models\OrderItems
     * @uses App\Models\Inventory
     */
    public function postOrder(createRequest $request)
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
            foreach($request->input('orderItems') as $product){
                $orderItems = new OrderItems;
                $orderItems->order_id = $orders->id;
                $orderItems->product_id = $product['id'];
                $orderItems->cost_price = $product['cost_price'];
                $orderItems->selling_price = $product['selling_price'];
                $orderItems->quantity = $product['quantity'];
                $orderItems->total_cost = $product['total_cost_price'];
                $orderItems->total_selling = $product['total_selling_price'];
                $orderItems->save();
                $inventory = new Inventory;
                $inventory->product_id = $product['id'];
                $inventory->user_id = Auth::user()->id;
                $inventory->in_out_qty = (0 - $product['quantity']);
                $inventory->remarks = 'Deducted from order transaction';
                $inventory->order_id = $orders->id;
                $inventory->save();
            }
            return response()->success('success');
        }
    }
    /**
     * @param App\Http\Requests\order\deleteRequest $request
     * @param int $id
     * @uses App\Models\Orders
     * @uses App\Models\OrderItems
     * @uses App\Models\Inventory
     */
    public function deleteOrder(deleteRequest $request, $id)
    {
        $order = Orders::find($id);
        $orderItems = OrderItems::where('order_id', $order->id);
        $inventory = Inventory::where('order_id',$order->id);
        $orderItems->delete();
        $inventory->delete();
        $order->delete();
    }
    /**
     * @param App\Http\Requests\order\viewRequest $request
     * @uses App\Models\Orders
     */
    public function getStatusCount(viewRequest $request)
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
    /**
     * @param App\Http\Requests\order\viewRequest $request
     * @uses App\Models\Orders
     */
    public function getCount(viewRequest $request)
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
