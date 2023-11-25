<?php

namespace App\Http\Requests;

use App\Http\Requests\Traits\ErrorThrownTrait;
use App\Http\Requests\Traits\MessageTrait;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    use ErrorThrownTrait, MessageTrait;
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
        return [
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'role_id' => 'required|exists:user_roles,id',
            'password' => 'required|min:8'
        ];
    }
}
