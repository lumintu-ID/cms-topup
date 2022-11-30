<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PriceRequest extends FormRequest
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
            "ppi" => 'required',
            "currency" => 'required',
            "game" => 'required',
            "payment" => 'required',
            "currency" => 'required',
            "name" => 'required',
            "amount" => "required|numeric",
            "price" => 'required|numeric'
        ];
    }
}
