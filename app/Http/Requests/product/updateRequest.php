<?php

namespace App\Http\Requests\product;

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
        return $this->user->can('update.product');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'required|integer',
            'product_code' => 'required',
            'product_name' => 'required',
            'size' => 'required',
            'description' => 'required',
            'cost_price' => 'required',
            'selling_price' => 'required',
        ];
    }
}
