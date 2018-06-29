<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function getIndex(){
        $customers = Customer::all();
        return response()->success(compact('customers'));
    }

    public function getCustomer($id){
        $customer = Customer::find($id);
        return response()->success($customer);
    }

    public function postCustomer(Request $request){
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

    public function putCustomer(Request $request){
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

    public function deleteCustomer($id){
        $customer = Customer::find($id);
        if($customer->delete()){
            return response()->success('success');
        }
    }

    public function getCount(Request $request)
    {
        $count = Customer::count();
        return response()->success($count);
    }
}
