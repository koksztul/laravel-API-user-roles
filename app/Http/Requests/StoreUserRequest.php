<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Hash;

class StoreUserRequest extends FormRequest
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
            'fname' => 'required|max:255',
            'lname' => 'required|max:255',
            'login' => 'required|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'type' => 'required|max:255',
            'password' => 'required|string|min:8',
        ];
    }
    protected function prepareForValidation()
    {
        $input = array_map('trim', $this->all());
        if (isset($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        }
        $this->replace($input);
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
        'errors' => $validator->errors(),
        'status' => true
        ], 422));
    }
}
