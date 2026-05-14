<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreRoadmapItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('access-admin') ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:65535'],
            'is_visible' => ['required', 'boolean'],
        ];
    }

    /**
     * @return array{title: string, sort_order: int, is_visible: bool}
     */
    public function formData(): array
    {
        $validated = $this->validated();

        return [
            'title' => trim($validated['title']),
            'sort_order' => (int) $validated['sort_order'],
            'is_visible' => $this->boolean('is_visible'),
        ];
    }
}
