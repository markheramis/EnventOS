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
            'first_name' => 'required|alpha',
            'last_name' => 'required|alpha',
            'email' => 'required|email',
            'phone' => 'required|integer',
            'address' => 'required',
            'state' => 'required',
            'city' => 'required|alpha',
            'zip' => 'required|integer',
            'company_name' => 'required|alpha_num'
        ];
    }
}
