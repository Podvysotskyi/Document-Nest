<?php

namespace App\Services;

use App\DTOs\SavedDocumentFilterData;
use App\Models\SavedDocumentFilter;
use App\Models\User;
use App\Repositories\SavedDocumentFilterRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;

class SavedDocumentFilterService
{
    /**
     * @var array<int, string>
     */
    private const FILTER_KEYS = [
        'q',
        'category_id',
        'tag_id',
        'status',
        'expiry_from',
        'expiry_to',
    ];

    public function __construct(private SavedDocumentFilterRepository $savedDocumentFilterRepository) {}

    /**
     * @return Collection<int, SavedDocumentFilter>
     */
    public function listForUser(User $user): Collection
    {
        return $this->savedDocumentFilterRepository->listForUser($user);
    }

    public function createForUser(User $user, SavedDocumentFilterData $data): SavedDocumentFilter
    {
        if ($data->isDefault) {
            $this->savedDocumentFilterRepository->clearDefaultsForUser($user);
        }

        return $this->savedDocumentFilterRepository->createForUser($user, $this->attributesFromData($data));
    }

    /**
     * @return array{name: string, filters: array<string, mixed>, sort: ?string, direction: ?string, is_default: bool}
     */
    private function attributesFromData(SavedDocumentFilterData $data): array
    {
        return [
            'name' => $data->name,
            'filters' => $this->normalizeFilters($data->filters),
            'sort' => filled($data->sort) ? $data->sort : null,
            'direction' => filled($data->direction) ? $data->direction : null,
            'is_default' => $data->isDefault,
        ];
    }

    /**
     * @param  array<string, mixed>  $filters
     * @return array<string, string>
     */
    public function normalizeFilters(array $filters): array
    {
        return collect(Arr::only($filters, self::FILTER_KEYS))
            ->map(fn (mixed $value): mixed => is_string($value) ? trim($value) : $value)
            ->reject(fn (mixed $value): bool => $value === null || $value === '')
            ->all();
    }

    public function update(SavedDocumentFilter $savedDocumentFilter, SavedDocumentFilterData $data): SavedDocumentFilter
    {
        if ($data->isDefault) {
            $this->savedDocumentFilterRepository->clearDefaultsForUser($savedDocumentFilter->user, $savedDocumentFilter);
        }

        return $this->savedDocumentFilterRepository->update($savedDocumentFilter, $this->attributesFromData($data));
    }

    public function delete(SavedDocumentFilter $savedDocumentFilter): void
    {
        $this->savedDocumentFilterRepository->delete($savedDocumentFilter);
    }
}
