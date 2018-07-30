<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use App\Http\Requests;
use App\Models\Product;
use App\Models\Inventory;

use App\Http\Requests\product\createRequest;
use App\Http\Requests\product\deleteRequest;
use App\Http\Requests\product\updateRequest;
use App\Http\Requests\product\viewRequest;

class ProductController extends Controller
{
    /**
     * @param App\Http\Requests\product\viewRequest $request
     * @uses App\Models\Product
     * @todo apply safe delete, query only those who are not safe deleted
     */
    public function getIndex(viewRequest $request)
    {
        $with_stock_only = $request->query('with_stock_only');
        $sort_by = $request->query('sort_by');
        $search = $request->query('search');
        /*
         * Dirty solution solved
         * @credits: Cedrick Blas @ ProgrammersDevelopers
         */
        $products = Product::select('products.*',DB::raw('SUM(inventories.in_out_qty) as on_hand'))
        ->join('inventories','products.id','=','inventories.product_id')
        ->groupBy('inventories.product_id')
        ->when($with_stock_only, function($query) use ($with_stock_only) {
            return $query->havingRaw('SUM(inventories.in_out_qty) > 0');
        })
        ->when($sort_by, function($query) use ($sort_by) {
            return $query->orderBy($sort_by,'desc');
        })
        ->when($search, function($query) use ($search){
            return $query
                ->where('product_code','like','%'.$search.'%')
                ->orWhere('product_name','like','%'.$search.'%');
        })
        ->get();
        # BEGIN DIRTY SOLUTION
        #foreach($products as $product)
        #{
            // is there a shortcut for this? something like a single query solution?
            #$product->on_hand = $product->inventory()->sum('in_out_qty');
        #}
        # END DIRTY SOLUTION
        return response()->success(compact('products'));
    }
    /**
     * @param App\Http\Requests\product\viewRequest $request
     * @uses App\Models\Product
     * @todo apply safe delete feature
     */
    public function getProduct(viewRequest $request, $id)
    {
        $product = Product::find($id);
        if(!empty($product))
        {
            return response()->success($product);
        }
        else
        {
            return response()->error('No product found');
        }
    }
    /**
     * @param App\Http\Requests\product\createRequest $request
     * @uses App\Models\Product
     * @uses App\Models\Inventory
     */
    public function postProduct(createRequest $request)
    {
        $product = new Product;
        $product->product_code = $request->input('product_code');
        $product->product_name = $request->input('product_name');
        $product->size = $request->input('size');
        $product->description = $request->input('description');
        $product->cost_price = $request->input('cost_price');
        $product->selling_price = $request->input('selling_price');
        if($product->save())
        {
            $inventory = new Inventory;
            $inventory->product_id = $product->id;
            $inventory->user_id = Auth::user()->id;
            $inventory->in_out_qty = $request->input('quantity');
            $inventory->remarks = 'Initial product inventory';
            if($inventory->save())
            {
                return response()->success($product->id);
            }
            else
            {
                return response()->error('Error in saving inventory.');
            }
        }
        else
        {
            return response()->error('Error in saving product.');
        }
    }
    /**
     * @param App\Http\Requests\product\updateRequest $request
     * @uses App\Models\Product
     */
    public function putProduct(updateRequest $request)
    {
        $data = $request->input('data');
        $product = Product::find($data['id']);
        $product->product_code = $data['product_code'];
        $product->product_name = $data['product_name'];
        $product->size = $data['size'];
        $product->description = $data['description'];
        $product->cost_price = $data['cost_price'];
        $product->selling_price = $data['selling_price'];
        if($product->save())
        {
            return response()->success('success');
        }
        else
        {
            return response()->error('Error in updating product.');
        }
    }

    /**
     * @param App\Http\Requests\product\deleteRequest $request
     * @uses App\Models\Product
     * @todo Apply safe delete
     */
    public function deleteProduct(deleteRequest $request, $id)
    {
        $product = Product::find($id);
        if($product->delete())
        {
            return response()->success('success');
        }
        else
        {
            return response()->error('Error in deleting product.');
        }
    }

    /**
     * @param App\Http\Requests\product\viewRequest $request
     * @uses App\Models\Product
     */
    public function getCount(viewRequest $request)
    {
        $type = ($request->query('type')) ? $request->query('type') : false;
        $count = Product::when($type,function($query) use ($type) {
            $query->where('type',$type);
        })
        ->count();
        return response()->success($count);
    }
}
