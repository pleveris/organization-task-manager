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
            'title'           => ['required'],
            'description'     => ['required'],
            'deadline'        => ['nullable', 'date'],
            'user_id'         => ['required', 'exists:users,id'],
            'organization_id' => ['required', 'exists:organizations,id'],
            'status'          => ['required'],
        ];
    }
}
