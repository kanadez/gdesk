<?php
/**
 * Created by PhpStorm.
 * User: a6y
 * Date: 30.07.18
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
            'description' => 'required|string|max:255',
            'images' => 'required|array|min:1',
            'tags'    => 'required|array|min:1',
            'tags.*'  => 'required|string|distinct|min:3',
            'title' => 'required|string|max:100',
        ];
    }
}
