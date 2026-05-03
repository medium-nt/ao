<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'service_id' => ['required', 'exists:services,id'],
            'status_id' => ['nullable', 'exists:service_statuses,id'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'price' => ['required', 'numeric', 'min:0'],
            'note' => ['nullable', 'string'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'service_id.required' => 'Выберите услугу.',
            'service_id.exists' => 'Выбранная услуга не найдена.',
            'status_id.exists' => 'Выбранный статус не найден.',
            'start_date.required' => 'Дата начала обязательна.',
            'start_date.date' => 'Введите корректную дату начала.',
            'end_date.date' => 'Введите корректную дату завершения.',
            'end_date.after_or_equal' => 'Дата завершения не может быть раньше даты начала.',
            'price.required' => 'Стоимость обязательна.',
            'price.numeric' => 'Стоимость должна быть числом.',
            'price.min' => 'Стоимость не может быть отрицательной.',
        ];
    }
}
