<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCertificateRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'category_id' => ['required', 'exists:categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'hours' => ['required', 'numeric', 'min:0.5', 'max:99999'],
            'file' => ['nullable', 'file', 'min:1', 'max:10240', 'mimes:pdf,jpg,jpeg,png,webp', 'extensions:pdf,jpg,jpeg,png,webp'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'category_id.required' => 'Selecione uma categoria.',
            'category_id.exists' => 'A categoria selecionada não existe.',
            'title.required' => 'O título do certificado é obrigatório.',
            'title.max' => 'O título não pode ter mais de 255 caracteres.',
            'hours.required' => 'A carga horária é obrigatória.',
            'hours.min' => 'A carga horária deve ser no mínimo 0.5 horas.',
            'hours.numeric' => 'A carga horária deve ser um número.',
            'file.file' => 'O envio deve ser um arquivo válido.',
            'file.min' => 'O arquivo parece estar vazio ou corrompido.',
            'file.max' => 'O arquivo não pode ter mais de 10MB.',
            'file.mimes' => 'O arquivo deve ser PDF, JPG, JPEG, PNG ou WEBP.',
            'file.extensions' => 'A extensão do arquivo deve ser PDF, JPG, JPEG, PNG ou WEBP.',
        ];
    }
}
