<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCourseRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'total_hours' => ['required', 'numeric', 'min:1', 'max:99999'],
            'categories' => ['required', 'array', 'min:1'],
            'categories.*.id' => ['nullable', 'integer', 'exists:categories,id'],
            'categories.*.name' => ['required', 'string', 'max:255'],
            'categories.*.max_hours' => ['required', 'numeric', 'min:0.5', 'max:99999'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'O nome do curso é obrigatório.',
            'total_hours.required' => 'A carga horária total é obrigatória.',
            'total_hours.min' => 'A carga horária deve ser no mínimo 1 hora.',
            'categories.required' => 'Adicione pelo menos uma categoria.',
            'categories.min' => 'Adicione pelo menos uma categoria.',
            'categories.*.name.required' => 'O nome da categoria é obrigatório.',
            'categories.*.max_hours.required' => 'As horas máximas da categoria são obrigatórias.',
            'categories.*.max_hours.min' => 'As horas máximas devem ser no mínimo 0.5.',
        ];
    }
}
