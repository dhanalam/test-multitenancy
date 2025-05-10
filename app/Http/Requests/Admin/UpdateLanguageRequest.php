<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLanguageRequest extends FormRequest
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
     * @return array<string, array<mixed>|\Illuminate\Contracts\Validation\ValidationRule|string>
     */
    public function rules(): array
    {
        return [
            'country_id' => 'required|string|exists:countries,id',
            'name' => ['required', 'string', 'max:250', Rule::unique('languages', 'name')->where('country_id', $this->get('country_id'))->ignore($this->route('language')->id)],
            'code' => ['required', 'string', 'max:2', Rule::unique('languages', 'code')->where('country_id', $this->get('country_id'))->ignore($this->route('language')->id)],
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'default' => 'boolean',
        ];
    }
}
