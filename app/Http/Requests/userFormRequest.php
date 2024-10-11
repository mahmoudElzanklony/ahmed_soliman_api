<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class userFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if(request()->filled('id')) {
            return [
                'username' => 'filled',
                'phone' => 'filled|unique:users,phone,'. request('id'),
                'password' => 'filled',
                'type' => 'filled',
                'id'=>'required|exists:users,id'
            ];
        }else{
            return [
                'username' => 'required',
                'password' => 'required',
                'phone' => [
                    'required',
                    Rule::unique('users')->where(function ($query) {
                        return $query->whereNull('deleted_at');
                    }),
                ],
                'type' => 'required',
            ];
        }
    }

    public function attributes()
    {
        return [
          'phone'=>__('keywords.phone')
        ];
    }


}
