<?php

namespace App\Http\Requests;

use Auth;
use Dingo\Api\Http\FormRequest;

abstract class Request extends FormRequest
{
	protected $user = null;
    public function __construct(){
    	$this->user = Auth::user();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            
        ];
    }
}
