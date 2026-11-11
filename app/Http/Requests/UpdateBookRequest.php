<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
{
    return [
        'title' => ['sometimes', 'required', 'string', 'max:255'],
        'author' => ['sometimes', 'required', 'string', 'max:255'],
        'publisher' => ['sometimes', 'nullable', 'string', 'max:255'],
        'isbn' => [
            'sometimes', 'required', 'string', 'max:20',
            Rule::unique('books', 'isbn')->ignore($this->route('book')),
        ],
        'published_year' => ['sometimes', 'required', 'integer', 'min:1000', 'max:'.date('Y')],
        'stock' => ['sometimes', 'required', 'integer', 'min:0'],
        'description' => ['sometimes', 'nullable', 'string'],
    ];
}
}
