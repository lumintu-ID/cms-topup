<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentUpdateRequest extends FormRequest
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
            'url' => 'required|url',
            'name' => 'required|string',
            'category' => 'required',
            'country' => 'required',
            'channel_id' => 'required',
            'code_payment' => 'required',
            'thumbnail' => 'file|image|mimes:jpeg,png,jpg|max:1048'
        ];
    }
}
