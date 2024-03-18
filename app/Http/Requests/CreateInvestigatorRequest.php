<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Knuckles\Scribe\Attributes\BodyParam;

#[BodyParam("first_name", "string", "Investigator's first name", required: true, example: 'Joe')]
#[BodyParam("middle_initial", "string", "Investigator's middle initial", required: true, example: 'A')]
#[BodyParam("last_name", "string", "Investigator's last name", required: true, example: 'Bloggs')]
#[BodyParam("email", "string", "Investigator's email address", required: true, example: 'joe.bloggs@example.com')]
class CreateInvestigatorRequest extends FormRequest
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
            'first_name'     => 'required',
            'middle_initial' => 'required',
            'last_name'      => 'required',
            'email'          => 'required|email'
        ];
    }
}
