<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Models\VaccineCenter;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class UserRequest extends FormRequest
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
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'. User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'nid' => 'required|digits:17|unique:'. User::class,
            'vaccine_center_id' => 'required|exists:' . VaccineCenter::class .',id',
        ];
    }

    public function messages()
    {
        return [
            'nid.digits' => 'NID must be digit',
            'nid.unique' => 'NID already exists.',
            'vaccine_center_id.required' => 'Vaccine Center is Required',
            'vaccine_center_id.exits' => 'Vaccine Center is not exits',
        ];
    }
}
