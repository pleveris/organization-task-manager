<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditTaskRequest extends FormRequest
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
            'parent_id'       => 'nullable|exists:tasks,id',
            'title'           => ['required'],
            'description'     => ['nullable'],
            'deadline'        => ['nullable', 'date'],
            'user_id'         => ['nullable', 'exists:users,id'],
            'organization_id' => ['required', 'exists:organizations,id'],
            'logic_test'      => ['nullable'],
            'logic'           => 'nullable',
            'hidden'          => 'nullable',
            'expiration_date' => 'nullable',
        ];
    }
}
