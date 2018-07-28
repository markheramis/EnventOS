<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use App\Http\Requests;
use App\Models\Item;
use App\Models\ItemKitItem;
use App\Models\Inventory;

use App\Http\Requests\item\createRequest;
use App\Http\Requests\item\deleteRequest;
use App\Http\Requests\item\updateRequest;
use App\Http\Requests\item\viewRequest;

class ItemController extends Controller
{
    /**
     * @param App\Http\Requests\item\viewRequest $request
     * @uses App\Models\Item
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
        $items = Item::select('items.*',DB::raw('SUM(inventories.in_out_qty) as on_hand'))
        ->join('inventories','items.id','=','inventories.item_id')
        ->groupBy('inventories.item_id')
        ->when($with_stock_only, function($query) use ($with_stock_only) {
            return $query->havingRaw('SUM(inventories.in_out_qty) > 0');
        })
        ->when($sort_by, function($query) use ($sort_by) {
            return $query->orderBy($sort_by,'desc');
        })
        ->when($search, function($query) use ($search){
            return $query
                ->where('item_code','like','%'.$search.'%')
                ->orWhere('item_name','like','%'.$search.'%');
        })
        ->get();
        # BEGIN DIRTY SOLUTION
        #foreach($items as $item)
        #{
            // is there a shortcut for this? something like a single query solution?
            #$item->on_hand = $item->inventory()->sum('in_out_qty');
        #}
        # END DIRTY SOLUTION
        return response()->success(compact('items'));
    }
    /**
     * @param App\Http\Requests\item\viewRequest $request
     * @uses App\Models\Item
     * @todo apply safe delete feature
     */
    public function getItem(viewRequest $request, $id)
    {
        $item = Item::find($id);
        if(!empty($item))
        {
            return response()->success($item);
        }
        else
        {
            return response()->error('No item found');
        }
    }
    /**
     * @param App\Http\Requests\item\createRequest $request
     * @uses App\Models\Item
     * @uses App\Models\Inventory
     */
    public function postItem(createRequest $request)
    {
        $item = new Item;
        $item->item_code = $request->input('item_code');
        $item->item_name = $request->input('item_name');
        $item->size = $request->input('size');
        $item->description = $request->input('description');
        $item->cost_price = $request->input('cost_price');
        $item->selling_price = $request->input('selling_price');
        if($item->save())
        {
            $inventory = new Inventory;
            $inventory->item_id = $item->id;
            $inventory->user_id = Auth::user()->id;
            $inventory->in_out_qty = $request->input('quantity');
            $inventory->remarks = 'Initial item inventory';
            if($inventory->save())
            {
                return response()->success($item->id);
            }
            else
            {
                return response()->error('Error in saving inventory.');
            }
        }
        else
        {
            return response()->error('Error in saving item.');
        }
    }
    /**
     * @param App\Http\Requests\item\updateRequest $request
     * @uses App\Models\Item
     */
    public function putItem(updateRequest $request)
    {
        $data = $request->input('data');
        $item = Item::find($data['id']);
        $item->item_code = $data['item_code'];
        $item->item_name = $data['item_name'];
        $item->size = $data['size'];
        $item->description = $data['description'];
        $item->cost_price = $data['cost_price'];
        $item->selling_price = $data['selling_price'];
        if($item->save())
        {
            return response()->success('success');
        }
        else
        {
            return response()->error('Error in updating item.');
        }
    }

    /**
     * @param App\Http\Requests\item\deleteRequest $request
     * @uses App\Models\Item
     * @todo Apply safe delete
     */
    public function deleteItem(deleteRequest $request, $id)
    {
        $item = Item::find($id);
        if($item->delete())
        {
            return response()->success('success');
        }
        else
        {
            return response()->error('Error in deleting item.');
        }
    }

    /**
     * @param App\Http\Requests\item\viewRequest $request
     * @uses App\Models\Item
     */
    public function getCount(viewRequest $request)
    {
        $type = ($request->query('type')) ? $request->query('type') : false;
        $count = Item::when($type,function($query) use ($type) {
            $query->where('type',$type);
        })
        ->count();
        return response()->success($count);
    }
}
