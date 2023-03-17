<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PricePointRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'game' => 'required',
            'country' => 'required',
            'price_point' => 'required',
            'amount' => 'required',
            'price' => 'required',
        ];
    }
}
