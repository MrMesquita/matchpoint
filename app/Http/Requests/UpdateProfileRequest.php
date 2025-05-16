<?php

namespace App\Http\Requests;

use App\Dtos\UpdateProfileDTO;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'phone' => [
                'required',
                Rule::unique('users')->ignore($this->user()->id)
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($this->user()->id)
            ]
        ];
    }

    public function toDTO(): UpdateProfileDTO
    {
        return new UpdateProfileDTO(
            name: $this->input('name'),
            surname: $this->input('surname'),
            phone: $this->input('phone'),
            email: $this->input('email')
        );
    }
}
