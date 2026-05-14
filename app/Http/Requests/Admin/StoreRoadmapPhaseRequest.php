<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreRoadmapPhaseRequest extends FormRequest
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
            'label' => ['required', 'string', 'max:80'],
            'title' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:80'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:65535'],
            'is_visible' => ['required', 'boolean'],
        ];
    }

    /**
     * @return array{label: string, title: string, status: string, sort_order: int, is_visible: bool}
     */
    public function formData(): array
    {
        $validated = $this->validated();

        return [
            'label' => trim($validated['label']),
            'title' => trim($validated['title']),
            'status' => trim($validated['status']),
            'sort_order' => (int) $validated['sort_order'],
            'is_visible' => $this->boolean('is_visible'),
        ];
    }
}
