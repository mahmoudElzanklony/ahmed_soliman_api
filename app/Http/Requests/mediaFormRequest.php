<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class mediaFormRequest extends FormRequest
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
        $arr = [
            'id'=>'filled',
            'category_id'=>'required|exists:categories,id',
            'name'=>'required|string',
            'info'=>'nullable|string',
            'file_name'=>'filled|mimes:png,jpg,jpeg,svg,mp4,mp3,pdf',
            'file_type'=>'filled|in:audio,video,image',

        ];
        return $arr;
    }

    public function attributes()
    {
        return [
            'category_id'=>trans('keywords.category_id'),
            'name'=>trans('keywords.name'),
            'info'=>trans('keywords.info'),
            'file_name'=>trans('keywords.file_name'),
            'file_type'=>trans('keywords.file_type'),
        ];
    }
}
