<?php

namespace App\Http\Requests;

use App\DTOs\SavedDocumentFilterData;
use App\Models\SavedDocumentFilter;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSavedDocumentFilterRequest extends FormRequest
{
    public function toDto(): SavedDocumentFilterData
    {
        $validated = $this->validated();

        return new SavedDocumentFilterData(
            name: trim($validated['name']),
            filters: $validated['filters'] ?? [],
            sort: $validated['sort'] ?? null,
            direction: $validated['direction'] ?? null,
            isDefault: $this->boolean('is_default'),
        );
    }

    public function authorize(): bool
    {
        return $this->user()->can('create', SavedDocumentFilter::class);
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $queryStateRules = IndexDocumentRequest::queryStateRules('filters');

        return [
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('saved_document_filters', 'name')->where(
                    fn ($query) => $query->where('user_id', $this->user()->id)
                ),
            ],
            'filters' => ['nullable', 'array'],
            ...array_diff_key($queryStateRules, array_flip(['filters.sort', 'filters.direction'])),
            'sort' => IndexDocumentRequest::queryStateRules()['sort'],
            'direction' => IndexDocumentRequest::queryStateRules()['direction'],
            'is_default' => ['sometimes', 'boolean'],
        ];
    }
}
