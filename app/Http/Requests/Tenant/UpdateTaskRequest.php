<?php

declare(strict_types=1);

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('task'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<mixed>|\Illuminate\Contracts\Validation\ValidationRule|string>
     */
    public function rules(): array
    {
        return [
            'project_id' => ['sometimes', 'required', Rule::exists('projects', 'id')],
            'is_active' => 'boolean',
            'order_no' => 'integer',
            'translations' => 'required|array',
            'translations.*.lang_id' => ['required', Rule::exists('languages', 'id')->where('country_id', getTenantId())],
            'translations.*.name' => 'required|string|max:255',
            'translations.*.description' => 'nullable|string|max:1000',
        ];
    }
}
