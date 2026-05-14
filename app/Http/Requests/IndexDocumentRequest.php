<?php

namespace App\Http\Requests;

use App\DTOs\DocumentFiltersData;
use App\Models\Document;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
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

        return new DocumentFiltersData(
            query: $validated['q'] ?? null,
            categoryId: $validated['category_id'] ?? null,
            tagId: $validated['tag_id'] ?? null,
            status: $validated['status'] ?? null,
            expiryFrom: $validated['expiry_from'] ?? null,
            expiryTo: $validated['expiry_to'] ?? null,
            sort: $validated['sort'] ?? 'newest',
        );
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'q' => ['nullable', 'string', 'max:255'],
            'category_id' => ['nullable', 'uuid'],
            'tag_id' => ['nullable', 'uuid'],
            'status' => ['nullable', Rule::in([
                Document::STATUS_ACTIVE,
                Document::STATUS_EXPIRED,
                Document::STATUS_ARCHIVED,
            ])],
            'expiry_from' => ['nullable', 'date'],
            'expiry_to' => ['nullable', 'date'],
            'sort' => ['nullable', Rule::in(['newest', 'oldest', 'title', 'expiry_date'])],
        ];
    }
}
