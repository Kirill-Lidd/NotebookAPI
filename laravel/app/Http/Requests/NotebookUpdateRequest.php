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
     * @return array
     */
    public function rules()
    {
        return [
            'full_name' => 'required|string',
            'company' => 'string',
            'phone' => 'required|string',
            'email' => 'required|string|email',
            'date_birth' => 'date',
            'photo' => 'image|mimes:jpg,png,jpeg'
        ];
    }

    public function messages()
    {
        return[
            'full_name.required' => 'Это поле обязательно',
            'phone.required' => 'Это поле обязательно',
            'email.required' => 'Это поле обязательно',
            'email.email' => 'Почта должна содержать @ и .',
            'date_birth.date' => 'Формат даты должен быть дд-мм-гггг',
            'photo.image' => 'Загружаемый файл должен быть изображением',
            'photo.mimes' => 'Загружамеый файл должен быть формата:.jpg, .png, .jpeg',
        ];
    }
}
