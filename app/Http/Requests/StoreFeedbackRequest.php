<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreFeedbackRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        if (! $this->has('action')) {
            $this->merge(['action' => 'submit']);
        }
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return (bool) $this->user()?->isStudent();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $action = $this->input('action', 'submit');

        if ($action === 'draft') {
            return [
                'action' => ['required', 'in:draft,submit'],
                'title' => ['nullable', 'string', 'max:255', 'required_without:message'],
                'message' => ['nullable', 'string', 'max:5000', 'required_without:title'],
            ];
        }

        return [
            'action' => ['required', 'in:draft,submit'],
            'title' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ];
    }
}
