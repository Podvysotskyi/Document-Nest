<?php

namespace App\Http\Requests;

use App\Models\Document;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

abstract class BulkDocumentActionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('viewAny', Document::class);
    }

    /**
     * @return array<int, string>
     */
    public function documentIds(): array
    {
        $documentIds = $this->validated('document_ids', []);

        if (! is_array($documentIds)) {
            return [];
        }

        return array_values(array_unique($documentIds));
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'document_ids' => ['required', 'array', 'min:1'],
            'document_ids.*' => [
                'required',
                'uuid',
                'distinct',
                Rule::exists('documents', 'id')->where(
                    fn (Builder $query): Builder => $query->where('user_id', $this->user()->id)
                ),
            ],
        ];
    }
}
