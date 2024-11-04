<?php

namespace App\Http\Requests\Tasks;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
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
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'priority' => 'sometimes|integer|min:0|max:10',
            'type' => 'sometimes|in:due_date,date_range,weekly,daily',
            'due_date' => 'sometimes|date|after_or_equal:today',
            'start_date' => 'sometimes|date|after_or_equal:today|before_or_equal:end_date',
            'end_date' => 'sometimes|date|after_or_equal:today|after_or_equal:start_date',
            'days_of_week' => 'sometimes|json',
            'time' => 'sometimes|date_format:H:i',
            'tags' => 'sometimes|array',
            'tags.*' => 'exists:tags,id',
        ];
    }
}
