<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\Supplier;

use App\Http\Requests\supplierGetAllRequest;
use App\Http\Requests\supplierGetSingleRequest;
use App\Http\Requests\supplierCreateRequest;
use App\Http\Requests\supplierUpdateRequest;
use App\Http\Requests\supplierDeleteRequest;

class SupplierController extends Controller
{
    public function getIndex(supplierGetAllRequest $request)
    {
        $suppliers = Supplier::all();
        return response()->success(compact('suppliers'));
    }

    public function getSupplier(supplierGetSingleRequest $request, $id)
    {
        $supplier = Supplier::find($id);
        return response()->success($supplier);
    }

    public function postSupplier(supplierCreateRequest $request)
    {
        try{
            $supplier = new Supplier;
            $supplier->first_name = $request->input('first_name');
            $supplier->last_name = $request->input('last_name');
            $supplier->email = $request->input('email');
            $supplier->phone = $request->input('phone');
            $supplier->address = $request->input('address');
            $supplier->state = $request->input('state');
            $supplier->city = $request->input('city');
            $supplier->zip = $request->input('zip');
            $supplier->company_name = $request->input('company_name');
            if($supplier->save())
            {
                return response()->success('success');
            }
        }
        catch(\Exception $ex)
        {
            return response()->error('Failed to create supplier');
        }
    }

    public function putSupplier(supplierUpdateRequest $request)
    {
        $data = $request->input('data');
        try{
            $supplier = Supplier::find($data['id']);
            $supplier->first_name = $data['first_name'];
            $supplier->last_name = $data['last_name'];
            $supplier->email = $data['email'];
            $supplier->phone = $data['phone'];
            $supplier->address = $data['address'];
            $supplier->state = $data['state'];
            $supplier->city = $data['city'];
            $supplier->zip = $data['zip'];
            $supplier->company_name = $data['company_name'];
            if($supplier->save())
            {
                return response()->success('success');
            }
        }
        catch(\Exception $ex)
        {
            return response()->error('Failed to update supplier');
        }


    }

    public function deleteSupplier(supplierDeleteRequest $request, $id)
    {
        try{
            $supplier = Supplier::find($id);
            $supplier->delete();
            return response()->success('success');
        }
        catch(\Exception $ex)
        {
            return response()->error('Failed to delete supplier');
        }
    }
}
