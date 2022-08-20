<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NotebookUpdateRequest extends FormRequest
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
            'full_name' => 'required|string',
            'company' => 'string',
            'phone' => 'required|string',
            'email' => 'required|string|email',
            'data_birth' => 'date',
            'photo' => 'image|mimes:jpg,png,jpeg'
        ];
    }

    public function messages()
    {
        return[
            'full_name.required' => 'Это поле обязательно',
            'phone.required' => 'Это поле обязательно',
            'email.required' => 'Это поле обязательно',
        ];
    }
}
