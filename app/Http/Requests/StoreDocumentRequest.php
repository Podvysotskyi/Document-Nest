<?php

namespace App\Http\Requests;

use App\DTOs\StoreDocumentData;
use App\Models\Document;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class StoreDocumentRequest extends FormRequest
{
    public function toDto(): StoreDocumentData
    {
        $validated = $this->validated();

        return new StoreDocumentData(
            file: $validated['file'],
            title: $validated['title'],
            categoryId: $validated['category_id'] ?? null,
            tagIds: $validated['tag_ids'] ?? [],
            notes: $validated['notes'] ?? null,
            issueDate: $validated['issue_date'] ?? null,
            expiryDate: $validated['expiry_date'] ?? null,
            status: $validated['status'] ?? null,
        );
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', Document::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'file' => [
                'required',
                File::types(['pdf', 'jpg', 'jpeg', 'png', 'webp'])->max(20 * 1024),
            ],
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
            'status' => ['nullable', Rule::in(['active', 'expired', 'archived'])],
        ];
    }
}
