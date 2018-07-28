<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\Supplier;

use App\Http\Requests\supplier\createRequest;
use App\Http\Requests\supplier\deleteRequest;
use App\Http\Requests\supplier\updateRequest;
use App\Http\Requests\supplier\viewRequest;

class SupplierController extends Controller
{
    /**
     * @param App\Http\Requests\supplier\viewRequest $request
     * @uses App\Models\Supplier
     */
    public function getIndex(viewRequest $request)
    {
        $suppliers = Supplier::all();
        return response()->success(compact('suppliers'));
    }
    /**
     * @param App\Http\Requests\supplier\viewRequest $request
     * @uses App\Models\Supplier
     */
    public function getSupplier(viewRequest $request, $id)
    {
        $supplier = Supplier::find($id);
        return response()->success($supplier);
    }
    /**
     * @param App\Http\Requests\supplier\createRequest $request
     * @uses App\Models\Supplier
     */
    public function postSupplier(createRequest $request)
    {
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
    /**
     * @param App\Http\Requests\supplier\updateRequest $request
     * @uses App\Models\Supplier
     */
    public function putSupplier(updateRequest $request)
    {
        $data = $request->input('data');
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
    /**
     * @param App\Http\Requests\supplier\deleteRequest $request
     * @uses App\Models\Supplier
     */
    public function deleteSupplier(deleteRequest $request, $id)
    {
        $supplier = Supplier::find($id);
        $supplier->delete();
        return response()->success('success');
    }
}
