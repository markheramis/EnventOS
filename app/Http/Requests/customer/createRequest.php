<?php

namespace App\Http\Requests\customer;

use App\Http\Requests\Request;

class createRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user->can('create.customer');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'phone' => 'required|integer',
            'address' => 'required',
            'state' => 'required',
            'city' => 'required',
            'zip' => 'required|integer',
            'company_name' => 'required'
        ];
    }
}
