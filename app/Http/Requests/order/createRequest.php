<?php

namespace App\Http\Requests\order;

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
        return $this->user->can('view.order');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'customer_id' => 'required|integer',
            'cost_price' => 'required',
            'selling_price' => 'required',
            'payment_amount' => 'required',
            'payment_type' => 'required|in:Cash,Check,Debit,Credit',
            'status' => 'required|in:complete,delivering,processing,cancelled',
            # Validating orderItems array
            'orderItems.*.id' => 'required',
            'orderItems.*.cost_price' => 'required',
            'orderItems.*.selling_price' => 'required',
            'orderItems.*.quantity' => 'required|integer',
            'orderItems.*.total_cost_price' => 'required',
            'orderItems.*.total_selling_price' => 'required'
        ];
    }
}
