<?php

namespace App\Http\Requests;

use App\Enums\DocumentType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClientDocumentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Получить правила валидации.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'file' => ['required', 'file', 'max:10240', 'mimes:pdf,doc,docx,xls,xlsx,jpg,png'],
            'type' => ['required', 'string', Rule::in(array_column(DocumentType::cases(), 'value'))],
        ];
    }

    /**
     * Получить сообщения об ошибках валидации.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'file.required' => 'Выберите файл для загрузки.',
            'file.max' => 'Размер файла не должен превышать 10 МБ.',
            'file.mimes' => 'Допустимые форматы: pdf, doc, docx, xls, xlsx, jpg, png.',
            'type.required' => 'Выберите тип документа.',
            'type.in' => 'Выбран недопустимый тип документа.',
        ];
    }
}
