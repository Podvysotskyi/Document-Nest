<?php

namespace App\Http\Requests;

use App\DTOs\DocumentFiltersData;
use App\Enums\DocumentStatus;
use App\Models\Document;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class IndexDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('viewAny', Document::class);
    }

    public function toDto(): DocumentFiltersData
    {
        $validated = $this->validated();
        $sort = $validated['sort'] ?? 'newest';
        $categoryFilter = $validated['category_id'] ?? null;

        return new DocumentFiltersData(
            query: $validated['q'] ?? null,
            categoryId: $categoryFilter === 'uncategorized' ? null : $categoryFilter,
            uncategorizedOnly: $categoryFilter === 'uncategorized',
            tagId: $validated['tag_id'] ?? null,
            status: isset($validated['status']) ? DocumentStatus::from($validated['status']) : null,
            expiryFrom: $validated['expiry_from'] ?? null,
            expiryTo: $validated['expiry_to'] ?? null,
            sort: $sort,
            direction: $validated['direction'] ?? ($sort === 'newest' ? 'desc' : 'asc'),
        );
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'q' => ['nullable', 'string', 'max:255'],
            'category_id' => [
                'nullable',
                'string',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if ($value !== 'uncategorized' && ! Str::isUuid($value)) {
                        $fail('The selected category is invalid.');
                    }
                },
            ],
            'tag_id' => ['nullable', 'uuid'],
            'status' => ['nullable', Rule::enum(DocumentStatus::class)],
            'expiry_from' => ['nullable', 'date'],
            'expiry_to' => ['nullable', 'date'],
            'sort' => ['nullable', Rule::in(['newest', 'oldest', 'title', 'expiry_date', 'status', 'category'])],
            'direction' => ['nullable', Rule::in(['asc', 'desc'])],
        ];
    }
}
