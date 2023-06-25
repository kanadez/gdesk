<?php
/**
 * Created by PhpStorm.
 * User: kanadez
 *
 * Time: 10:29
 */

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class SearchByCategoryRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'category' => 'required|int|exists:categories,id',
        ];
    }
}
