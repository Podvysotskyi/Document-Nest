<?php

namespace App\Http\Requests;

use App\DTOs\UpdateDocumentData;
use App\Enums\DocumentStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDocumentRequest extends FormRequest
{
    public function toDto(): UpdateDocumentData
    {
        $validated = $this->validated();

        return new UpdateDocumentData(
            title: $validated['title'],
            categoryId: $validated['category_id'] ?? null,
            tagIds: $validated['tag_ids'] ?? [],
            notes: $validated['notes'] ?? null,
            issueDate: $validated['issue_date'] ?? null,
            expiryDate: $validated['expiry_date'] ?? null,
            status: DocumentStatus::from($validated['status']),
        );
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('document'));
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
            'category_id' => [
                'nullable',
                'uuid',
                Rule::exists('categories', 'id')->where(
                    fn (Builder $query): Builder => $query->where('user_id', $this->user()->id)
                ),
            ],
            'tag_ids' => ['array'],
            'tag_ids.*' => [
                'uuid',
                Rule::exists('tags', 'id')->where(
                    fn (Builder $query): Builder => $query->where('user_id', $this->user()->id)
                ),
            ],
            'notes' => ['nullable', 'string'],
            'issue_date' => ['nullable', 'date'],
            'expiry_date' => ['nullable', 'date', 'after_or_equal:issue_date'],
            'status' => ['required', Rule::enum(DocumentStatus::class)],
        ];
    }
}
