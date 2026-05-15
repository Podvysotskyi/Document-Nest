<?php

namespace App\Services;

use App\Models\Sqlite\RoadmapItem;
use App\Models\Sqlite\RoadmapPhase;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class RoadmapService
{
    /**
     * @return array<int, array{id: int, label: string, title: string, status: string, sort_order: int, is_visible: bool, items: array<int, array{id: int, title: string, sort_order: int, is_visible: bool}>}>
     */
    public function visiblePhases(): array
    {
        return RoadmapPhase::query()
            ->where('is_visible', true)
            ->with([
                'items' => fn ($query) => $query
                    ->where('is_visible', true)
                    ->orderBy('sort_order')
                    ->orderBy('id'),
            ])
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->map(fn (RoadmapPhase $phase): array => $this->phaseToArray($phase))
            ->all();
    }

    /**
     * @return array<int, array{id: int, label: string, title: string, status: string, sort_order: int, is_visible: bool, items: array<int, array{id: int, title: string, sort_order: int, is_visible: bool}>}>
     */
    public function allPhasesForAdmin(): array
    {
        return RoadmapPhase::query()
            ->with([
                'items' => fn ($query) => $query
                    ->orderBy('sort_order')
                    ->orderBy('id'),
            ])
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->map(fn (RoadmapPhase $phase): array => $this->phaseToArray($phase))
            ->all();
    }

    /**
     * @param  array{label: string, title: string, status: string, sort_order: int, is_visible: bool}  $data
     * @return array{id: int, label: string, title: string, status: string, sort_order: int, is_visible: bool, items: array<int, array{id: int, title: string, sort_order: int, is_visible: bool}>}
     */
    public function createPhase(array $data): array
    {
        $phase = RoadmapPhase::query()->create($data);

        return $this->phaseToArray($phase->load('items'));
    }

    /**
     * @param  array{label: string, title: string, status: string, sort_order: int, is_visible: bool}  $data
     * @return array{id: int, label: string, title: string, status: string, sort_order: int, is_visible: bool, items: array<int, array{id: int, title: string, sort_order: int, is_visible: bool}>}
     */
    public function updatePhase(int $phaseId, array $data): array
    {
        $phase = RoadmapPhase::query()->findOrFail($phaseId);
        $phase->update($data);

        return $this->phaseToArray($phase->load([
            'items' => fn ($query) => $query
                ->orderBy('sort_order')
                ->orderBy('id'),
        ]));
    }

    public function deletePhase(int $phaseId): void
    {
        RoadmapPhase::query()->findOrFail($phaseId)->delete();
    }

    /**
     * @param  array{title: string, sort_order: int, is_visible: bool}  $data
     * @return array{id: int, title: string, sort_order: int, is_visible: bool}
     */
    public function createItem(int $phaseId, array $data): array
    {
        $phase = RoadmapPhase::query()->findOrFail($phaseId);
        $item = $phase->items()->create($data);

        return $this->itemToArray($item);
    }

    /**
     * @param  array{title: string, sort_order: int, is_visible: bool}  $data
     * @return array{id: int, title: string, sort_order: int, is_visible: bool}
     */
    public function updateItem(int $itemId, array $data): array
    {
        $item = RoadmapItem::query()->findOrFail($itemId);
        $item->update($data);

        return $this->itemToArray($item);
    }

    public function deleteItem(int $itemId): void
    {
        RoadmapItem::query()->findOrFail($itemId)->delete();
    }

    public function movePhase(int $phaseId, string $direction): void
    {
        DB::connection('sqlite')->transaction(function () use ($phaseId, $direction): void {
            $phases = RoadmapPhase::query()
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get();

            $index = $phases->search(fn (RoadmapPhase $phase): bool => $phase->id === $phaseId);

            abort_if($index === false, 404);

            $this->moveSortedModel($phases, $index, $direction);
        });
    }

    public function moveItem(int $itemId, string $direction): void
    {
        DB::connection('sqlite')->transaction(function () use ($itemId, $direction): void {
            $item = RoadmapItem::query()->findOrFail($itemId);
            $items = RoadmapItem::query()
                ->where('roadmap_phase_id', $item->roadmap_phase_id)
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get();

            $index = $items->search(fn (RoadmapItem $roadmapItem): bool => $roadmapItem->id === $itemId);

            abort_if($index === false, 404);

            $this->moveSortedModel($items, $index, $direction);
        });
    }

    /**
     * @param  Collection<int, RoadmapPhase|RoadmapItem>  $models
     */
    private function moveSortedModel(Collection $models, int $index, string $direction): void
    {
        $targetIndex = $direction === 'up' ? $index - 1 : $index + 1;

        if ($targetIndex < 0 || $targetIndex >= $models->count()) {
            return;
        }

        $orderedModels = $models->values();
        [$orderedModels[$index], $orderedModels[$targetIndex]] = [$orderedModels[$targetIndex], $orderedModels[$index]];

        $orderedModels->each(function (RoadmapPhase|RoadmapItem $model, int $index): void {
            $model->forceFill(['sort_order' => $index + 1])->save();
        });
    }

    /**
     * @return array{id: int, label: string, title: string, status: string, sort_order: int, is_visible: bool, items: array<int, array{id: int, title: string, sort_order: int, is_visible: bool}>}
     */
    private function phaseToArray(RoadmapPhase $phase): array
    {
        return [
            'id' => (int) $phase->id,
            'label' => $phase->label,
            'title' => $phase->title,
            'status' => $phase->status,
            'sort_order' => $phase->sort_order,
            'is_visible' => $phase->is_visible,
            'items' => $phase->items
                ->map(fn (RoadmapItem $item): array => $this->itemToArray($item))
                ->values()
                ->all(),
        ];
    }

    /**
     * @return array{id: int, title: string, sort_order: int, is_visible: bool}
     */
    private function itemToArray(RoadmapItem $item): array
    {
        return [
            'id' => (int) $item->id,
            'title' => $item->title,
            'sort_order' => $item->sort_order,
            'is_visible' => $item->is_visible,
        ];
    }
}
