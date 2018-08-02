<?php

namespace App\Http\Requests\customer;

use App\Http\Requests\Request;

class updateRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user->can('update.customer');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'data.id' => 'required|integer',
            'data.first_name' => 'required',
            'data.last_name' => 'required',
            'data.email' => 'required|email',
            'data.phone' => 'required|integer',
            'data.address' => 'required',
            'data.state' => 'required',
            'data.city' => 'required',
            'data.zip' => 'required|integer',
            'data.company_name' => 'required'
        ];
    }
}
