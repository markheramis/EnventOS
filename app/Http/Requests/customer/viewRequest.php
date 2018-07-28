<?php

namespace App\Http\Requests\customer;

use App\Http\Requests\Request;

class viewRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user->can('view.customer');
    }
}
