<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Knuckles\Scribe\Attributes\BodyParam;
use Knuckles\Scribe\Attributes\QueryParam;

#[BodyParam("name", "string", "Project name", required: true, example: 'The Project')]
#[BodyParam("investigator_id", "integer", "Investigator ID", required: true, example: 1)]
class CreateProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required',
            'investigator_id' => 'required|exists:investigators,id|integer'
        ];
    }
}
