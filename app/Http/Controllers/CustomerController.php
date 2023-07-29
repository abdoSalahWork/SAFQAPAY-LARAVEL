<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $profile = check_user($request->header('profile'));
        if ($profile) {
            $customers = Customer::where('profile_business_id', $profile->id)->with('country')->with('bank')->get();
            return response()->json(['data' => $customers]);
        }
        return response()->json(['message' => 'Please Choose Profile'], 404);
    }
    public function show($id)
    {
        $user = auth()->user();
        $customer = Customer::with('country')->with('bank')->find($id);
        if ($customer) {
            if ($user->profile_business_id == $customer->profile_business_id) {
                return response()->json(['data' => $customer]);
            }
        }
        return response()->json([
            'message' => 'Customer not found'
        ], 404);
    }
    public function store(Request $request)
    {

        $user = auth()->user();

        $validateData = Validator::make($data = $request->all(), [
            'full_name' => 'required|string|max:255',
            'phone_number_code_id' => 'required|integer|exists:countries,id',
            'phone_number' =>  'required|unique:customers,phone_number|max:20',

            'email' => 'email|unique:customers,email|nullable',
            'customer_reference' => 'string|max:255|nullable',
            'bank_id' =>  'integer|exists:banks,id|nullable',
            'bank_account' => 'string|max:255|nullable',
            'iban' => 'string|max:255|nullable',
        ]);

        if ($validateData->fails()) {
            return response()->json($validateData->errors(), 404);
        }

        $data['manager_user_id'] = $user->id;
        $data['profile_business_id'] = $user->profile_business_id;

        Customer::create($data);
        return response()->json([
            'message' => 'sucsess'
        ]);
    }
    function update(Request $request, $customerId)
    {

        $user = auth()->user();

        $customerUpdate = Customer::findOrFail($customerId);

        if ($user->profile_business_id == $customerUpdate->profile_business_id) {
            $validateData = Validator::make($data = $request->all(), [
                'full_name' => 'required|string|max:255',
                'email' => 'nullable|email|unique:customers,email,' . $customerId,

                'phone_number_code_id' => 'required|integer|exists:countries,id',
                'phone_number' =>  'required|max:20|unique:customers,phone_number,' . $customerId,

                'customer_reference' => 'string|max:255|nullable',
                'bank_id' =>  'integer|exists:banks,id|nullable',
                'bank_account' => 'string|max:255|nullable',
                'iban' => 'string|max:255|nullable',
            ]);

            if ($validateData->fails()) {
                return response()->json($validateData->errors(), 404);
            }

            $data['manager_user_id'] = $user->id;
            $customerUpdate->update($data);

            return response()->json([
                'message' => 'sucsess'
            ]);
        }
        return response()->json([
            'message' => 'You do not have permission to access this Customer'

        ], 404);
    }
    function delete($customerId)
    {

        $user = auth()->user();
        $customerDelete = Customer::findOrFail($customerId);


        if ($user->profile_business_id == $customerDelete->profile_business_id) {

            $customerDelete->delete();

            return response()->json([
                'message' => 'sucsess'
            ]);
        }
        return response()->json([
            'message' => 'You do not have permission to access this Customer'
        ], 404);
    }
}
