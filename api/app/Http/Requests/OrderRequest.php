<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
        return [
            'crypto_id' => 'required|numeric',
            'user_id' => 'required|max:255',
            'amount_thbt' => 'required|numeric',
            'amount_crypto' => 'required|numeric',
            'slippage' => 'required|numeric|between:0,1',
            'balance_thbt' => 'required|numeric'
        ];
    }
}
