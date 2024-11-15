<?php

namespace App\Http\Requests\Tasks;

use App\Rules\ValidDaysOfWeek;
use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'nullable|integer|min:0|max:10',
            'type' => 'nullable|in:due_date,date_range,weekly,daily',
            'due_date' => 'nullable|date|after_or_equal:today|prohibited_unless:type,due_date',
            'start_date' => 'nullable|date|after_or_equal:today|before_or_equal:end_date|prohibited_unless:type,date_range',
            'end_date' => 'nullable|date|after_or_equal:today|after_or_equal:start_date|prohibited_unless:type,date_range',
            'days_of_week' => ['nullable', new ValidDaysOfWeek()],
            'time' => 'nullable|date_format:H:i',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ];
    }
}
