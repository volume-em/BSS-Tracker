<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Knuckles\Scribe\Attributes\BodyParam;

#[BodyParam("first_name", "string", "Investigator's first name", required: false, example: 'Joe')]
#[BodyParam("middle_initial", "string", "Investigator's middle initial", required: false, example: 'A')]
#[BodyParam("last_name", "string", "Investigator's last name", required: false, example: 'Bloggs')]
#[BodyParam("email", "string", "Investigator's email address", required: false, example: 'joe.bloggs@example.com')]
class UpdateInvestigatorRequest extends FormRequest
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
            'first_name' => 'nullable',
            'middle_initial' => 'nullable',
            'last_name' => 'nullable',
            'email' => 'nullable|email'
        ];
    }
}
