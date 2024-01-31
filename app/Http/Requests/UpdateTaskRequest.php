<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTaskRequest extends FormRequest
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
        $taskId = $this->route('id'); // Assuming 'id' is the route parameter for the task ID

        return [
            'title' => [
                'required',
                'regex:/^[a-zA-Z0-9\s]+$/',
                'min:3',
                'max:255',
                Rule::unique('tasks', 'title')->ignore($taskId),
            ],
            // Add other validation rules as needed
        ];
    }
}
