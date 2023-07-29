<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class RegisterRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {

        $validtion = Validator::make(request()->all(), [
            'country_id' => "required|integer|exists:countries,id,country_active,1",
            'phone_number_code_id' => 'required|integer|exists:countries,id,country_active,1',
            'phone_number' =>  'required|max:20',
            'business_type_id' =>  'required|integer|max:20',
            'category_id' =>  'required|integer',
            'language_id' =>  'nullable|integer',

            'company_name' => 'required|string|max:255|unique:profile_businesses,company_name',
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'work_email' => 'required|email|max:255',

            'bank_account_name' => 'required|string|max:255',
            'bank_id' => "required|integer|exists:banks,id,country_id," . request()->country_id,
            'account_number' => 'required|string|max:255',
            'iban' => 'required|string|max:255',


            'email' => 'required|email|unique:users,email|unique:admins,email|max:255',
            'full_name' => 'required|string|max:255',
            'phone_number_code_manager_id' => 'required|string',
            'phone_number_manager' => 'required|unique:users,phone_number_manager|unique:admins,phone|min:8|max:20',
            'password' => 'required|confirmed|string|min:8|max:20', // password_confirmation
            'nationality_id' => 'required|integer',

        ]);

        if ($validtion->fails()) {

            return ['message' => $validtion->errors()];
        }

        return [];

    }
}
