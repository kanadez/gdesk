<?php
/**
 * Created by PhpStorm.
 * User: kanadez
 *
 * Time: 10:29
 */

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class SearchByQueryRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'query' => 'required|string|max:100',
        ];
    }
}
