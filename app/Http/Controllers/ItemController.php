<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use App\Http\Requests;
use App\Models\Item;
use App\Models\ItemKitItem;
use App\Models\Inventory;

class ItemController extends Controller
{
    public function getIndex(Request $request)
    {
        if($request->query('type') !== null)
        {
            $items = Item::where('type',$request->query('type'))->get();
        }
        else
        {
            $items = Item::get();
        }
        # BEGIN DIRTY SOLUTION

        foreach($items as $item)
        {
            $item->on_hand = $item->inventory()->sum('in_out_qty');
        }
        
        # END DIRTY SOLUTION
        return response()->success(compact('items'));
    }

    public function getItem($id)
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

    public function postItem(Request $request)
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

    public function putItem(Request $request)
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


    public function deleteItem($id)
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



    public function getKit($id)
    {
        $item = Item::find($id);
        if($item->type = 2)
        {
            $item->kitItems = $item->kitItems()
            ->join('items','items.id','=','item_kit_items.item_id')
            ->select(
                'items.cost_price',
                'items.selling_price',
                'item_kit_items.total_cost_price',
                'item_kit_items.total_selling_price',
                'item_kit_items.quantity',
                'items.id',
                'items.item_name',
                'items.item_code'
            )
            ->get();
        }
        if(!empty($item))
        {
            return response()->success($item);
        }
        else
        {
            return response()->error('No item found');
        }

    }

    public function putKit(Request $request)
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
            // Delete kitItems
            $kitItem = ItemKitItem::where('item_kit_id',$item->id)->delete();
            // Save kitItems again
            foreach($data['kitItems'] as $e)
            {
                $kitItem = new ItemKitItem;
                $kitItem->item_kit_id = $item->id;
                $kitItem->item_id = $e['id'];
                $kitItem->quantity = $e['quantity'];
                $kitItem->total_cost_price = $e['total_cost_price'];
                $kitItem->total_selling_price = $e['total_selling_price'];
                $kitItem->save();
            }

            return response()->success($data['kitItems']);
        }
        else
        {
            return response()->error('Error in updating item.');
        }
    }

    public function postKit(Request $request)
    {
        $item = new Item;
        $item->item_code = $request->input('item_code');
        $item->item_name = $request->input('item_name');
        $item->size = 'N/A';
        $item->description = $request->input('description');
        $item->cost_price = $request->input('cost_price');
        $item->selling_price = $request->input('selling_price');
        $item->type = 2;
        if($item->save())
        {
            foreach($request->input('items') as $itemKitItem)
            {
                $kitItem = new ItemKitItem;
                $kitItem->item_kit_id = $item->id;
                $kitItem->item_id = $itemKitItem['id'];
                $kitItem->quantity = $itemKitItem['quantity'];
                $kitItem->total_cost_price = $itemKitItem['total_cost_price'];
                $kitItem->total_selling_price = $itemKitItem['total_selling_price'];

                $kitItem->save();
            }
            return response()->success('success');
        }
    }

    public function deleteKit($id)
    {
        $item = Item::find($id);
        $itemKitItems = $item->kitItems;
        try
        {
            foreach($itemKitItems as $itemKitItem)
            {
                $itemKitItem->delete();
            }
            $item->delete();
            return response()->success($item);
        }
        catch(\Exception $e)
        {
            return response()->error($e);
        }


    }
}