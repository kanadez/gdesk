<?php
/**
 * Created by PhpStorm.
 * User: kanadez
 *
 * Time: 10:29
 */

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class CreateLocationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'category' => 'required|integer|exists:categories,id',
            'route' => 'nullable|integer|exists:routes,id',
            'description' => 'required|string|max:1000',
            'images' => 'required|array|min:1',
            'tags'    => 'required|array|min:1',
            'tags.*'  => 'required|string|min:2',
            'title' => 'required|string|max:255',
        ];
    }
}
