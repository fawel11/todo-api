<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTaskRequest extends FormRequest
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
            'title' => 'required|regex:/^[a-zA-Z0-9\s]+$/|min:3|max:255|unique:tasks',
        ];
    }

    public function messages()
    {
        return [
            'title.regex' => 'Title only accepts characters, numbers, and spaces.',
            'title.unique' => "Title can't be duplicate.",
        ];
    }
}
