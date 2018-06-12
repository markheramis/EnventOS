<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use App\Http\Requests;
use App\Models\Item;
use App\Models\ItemKitItem;


class ItemKitsController extends Controller
{
    public function getIndex()
    {
        $itemkits = Item::where('type',2)->get();
        return response()->success(compact('itemkits'));
    }

    public function getItemKit($id)
    {

    }

    public function postItemKit(Request $request)
    {

    }

    public function putItemKit(Request $reqeust)
    {

    }

    public function deleteItemKit($id)
    {
        $itemKit = Item::find($id);
        $itemKitItems = ItemKitItem::where('item_kit_id', $itemKit->id);
        if($itemKitItems->delete())
        {
            if($itemKit->delete())
            {
                return response()->success();
            }
            else
            {
                // Error deleting item kit
            }
        }
        else
        {
            // Error deleting item kit items
        }
    }
}
