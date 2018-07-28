<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\Customer;
use App\Http\Requests\customerCreateRequest;
use App\Http\Requests\customerUpdateRequest;


use App\Http\Requests\customer\createRequest;
use App\Http\Requests\customer\deleteRequest;
use App\Http\Requests\customer\updateRequest;
use App\Http\requests\customer\viewRequest;

class CustomerController extends Controller
{
    /**
     * @param App\Http\requests\customer\viewRequest $request
     * @uses App\Models\Customer
     */
    public function getIndex(viewRequest $request){
        $customers = Customer::all();
        return response()->success(compact('customers'));
    }
    /**
     * @param App\Http\requests\customer\viewRequest $request
     * @uses App\Models\Customer
     */
    public function getCustomer(viewRequest $request, $id){
        $customer = Customer::find($id);
        return response()->success($customer);
    }
    /**
     * @param App\Http\requests\customer\createRequest $request
     * @uses App\Models\Customer
     */
    public function postCustomer(createRequest $request){
        $customer = new Customer;
        $customer->first_name = $request->input('first_name');
        $customer->last_name = $request->input('last_name');
        $customer->email = $request->input('email');
        $customer->phone = $request->input('phone');
        $customer->address = $request->input('address');
        $customer->state = $request->input('state');
        $customer->city = $request->input('city');
        $customer->zip = $request->input('zip');
        $customer->company_name = $request->input('company_name');
        if($customer->save()){
            return response()->success('success');
        }
    }
    /**
     * @param App\Http\requests\customer\updateRequest $request
     * @uses App\Models\Customer
     */
    public function putCustomer(updateRequest $request){
        $data = $request->input('data');
        $customer = Customer::find($data['id']);
        $customer->first_name = $data['first_name'];
        $customer->last_name = $data['last_name'];
        $customer->email = $data['email'];
        $customer->phone = $data['phone'];
        $customer->address = $data['address'];
        $customer->state = $data['state'];
        $customer->city = $data['city'];
        $customer->zip = $data['zip'];
        $customer->company_name = $data['company_name'];
        if($customer->save()){
            return response()->success('success');
        }
    }
    /**
     * @param App\Http\requests\customer\deleteRequest $request
     * @uses App\Models\Customer
     */
    public function deleteCustomer(deleteRequest $request,$id){
        $customer = Customer::find($id);
        if($customer->delete()){
            return response()->success('success');
        }
        
    }
    /**
     * @param App\Http\requests\customer\viewRequest $request
     * @uses App\Models\Customer
     */
    public function getCount(viewRequest $request)
    {
        $count = Customer::count();
        return response()->success($count);
    }
}
