<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserValidation extends FormRequest
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
            'email' => 'email|required|unique:users,email,' . $this->id,
            'password' => 'nullable|min:4 ',
            'firstName' => 'required|max:20|alpha',
            'lastName' => 'required|max:20|alpha',
            'address' => 'nullable',
            'gender' => 'required|boolean',
            'phone' => 'size:8|unique:users,phone,' . $this->id,
            'experienceLevel' => 'required|max:20|in:beginner,intermediate,advanced,expert',
            'hiringDate' =>'date|required',
            'endOfContractDate' => 'date|after_or_equal:hiringDate',
            'company_id' => 'required',
            'department_id' => 'required',
            'position' => 'nullable|in:web developer,frontend developer,backend developer,mobile developer,IT,network security,sales'
        ];
    }
}
