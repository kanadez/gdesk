<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class Request extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }

    public function sanitize() {
        $input = $this->all();
        array_walk_recursive($input, function(&$input) {
            if(is_string($input)) {
                $input = filter_var($input, FILTER_SANITIZE_STRING);
            }
      	});
      	$this->merge($input);
    }

}
