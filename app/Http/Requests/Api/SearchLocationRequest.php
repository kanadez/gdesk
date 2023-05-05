<?php
/**
 * Created by PhpStorm.
 * User: a6y
 * Date: 30.07.18
 * Time: 10:29
 */

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class SearchLocationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'category' => 'nullable|string|max:1000',
            'query' => 'nullable|string|max:1000',
        ];
    }
}
