<?php

namespace App\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class RoadmapService
{
    private const string Path = 'data/roadmap.json';

    /**
     * @return array<int, array{id: int, label: string, title: string, status: string, sort_order: int, is_visible: bool, items: array<int, array{id: int, title: string, sort_order: int, is_visible: bool}>}>
     */
    public function visiblePhases(): array
    {
        return $this->sortPhases(
            collect($this->allPhasesForAdmin())
                ->filter(fn (array $phase): bool => $phase['is_visible'])
                ->map(function (array $phase): array {
                    $phase['items'] = $this->sortItems(
                        collect($phase['items'])
                            ->filter(fn (array $item): bool => $item['is_visible'])
                            ->values()
                            ->all()
                    );

                    return $phase;
                })
                ->values()
                ->all()
        );
    }

    /**
     * @return array<int, array{id: int, label: string, title: string, status: string, sort_order: int, is_visible: bool, items: array<int, array{id: int, title: string, sort_order: int, is_visible: bool}>}>
     */
    public function allPhasesForAdmin(): array
    {
        return $this->sortPhases($this->read()['phases']);
    }

    /**
     * @param  array{label: string, title: string, status: string, sort_order: int, is_visible: bool}  $data
     * @return array{id: int, label: string, title: string, status: string, sort_order: int, is_visible: bool, items: array<int, array{id: int, title: string, sort_order: int, is_visible: bool}>}
     */
    public function createPhase(array $data): array
    {
        $roadmap = $this->read();
        $phase = [
            'id' => $this->nextPhaseId($roadmap['phases']),
            ...$data,
            'items' => [],
        ];

        $roadmap['phases'][] = $phase;
        $this->write($roadmap);

        return $phase;
    }

    /**
     * @param  array{label: string, title: string, status: string, sort_order: int, is_visible: bool}  $data
     * @return array{id: int, label: string, title: string, status: string, sort_order: int, is_visible: bool, items: array<int, array{id: int, title: string, sort_order: int, is_visible: bool}>}
     */
    public function updatePhase(int $phaseId, array $data): array
    {
        $roadmap = $this->read();
        $updatedPhase = null;

        foreach ($roadmap['phases'] as $index => $phase) {
            if ($phase['id'] !== $phaseId) {
                continue;
            }

            $updatedPhase = [
                ...$phase,
                ...$data,
            ];
            $roadmap['phases'][$index] = $updatedPhase;

            break;
        }

        abort_if($updatedPhase === null, 404);

        $this->write($roadmap);

        return $updatedPhase;
    }

    public function deletePhase(int $phaseId): void
    {
        $roadmap = $this->read();
        $initialCount = count($roadmap['phases']);

        $roadmap['phases'] = collect($roadmap['phases'])
            ->reject(fn (array $phase): bool => $phase['id'] === $phaseId)
            ->values()
            ->all();

        abort_if(count($roadmap['phases']) === $initialCount, 404);

        $this->write($roadmap);
    }

    /**
     * @param  array{title: string, sort_order: int, is_visible: bool}  $data
     * @return array{id: int, title: string, sort_order: int, is_visible: bool}
     */
    public function createItem(int $phaseId, array $data): array
    {
        $roadmap = $this->read();
        $item = [
            'id' => $this->nextItemId($roadmap['phases']),
            ...$data,
        ];
        $phaseWasFound = false;

        foreach ($roadmap['phases'] as $index => $phase) {
            if ($phase['id'] !== $phaseId) {
                continue;
            }

            $roadmap['phases'][$index]['items'][] = $item;
            $phaseWasFound = true;

            break;
        }

        abort_unless($phaseWasFound, 404);

        $this->write($roadmap);

        return $item;
    }

    /**
     * @param  array{title: string, sort_order: int, is_visible: bool}  $data
     * @return array{id: int, title: string, sort_order: int, is_visible: bool}
     */
    public function updateItem(int $itemId, array $data): array
    {
        $roadmap = $this->read();
        $updatedItem = null;

        foreach ($roadmap['phases'] as $phaseIndex => $phase) {
            foreach ($phase['items'] as $itemIndex => $item) {
                if ($item['id'] !== $itemId) {
                    continue;
                }

                $updatedItem = [
                    ...$item,
                    ...$data,
                ];
                $roadmap['phases'][$phaseIndex]['items'][$itemIndex] = $updatedItem;

                break 2;
            }
        }

        abort_if($updatedItem === null, 404);

        $this->write($roadmap);

        return $updatedItem;
    }

    public function deleteItem(int $itemId): void
    {
        $roadmap = $this->read();
        $itemWasDeleted = false;

        foreach ($roadmap['phases'] as $phaseIndex => $phase) {
            $items = collect($phase['items'])
                ->reject(fn (array $item): bool => $item['id'] === $itemId)
                ->values()
                ->all();

            if (count($items) === count($phase['items'])) {
                continue;
            }

            $roadmap['phases'][$phaseIndex]['items'] = $items;
            $itemWasDeleted = true;

            break;
        }

        abort_unless($itemWasDeleted, 404);

        $this->write($roadmap);
    }

    public function movePhase(int $phaseId, string $direction): void
    {
        $roadmap = $this->read();
        $phases = $this->sortPhases($roadmap['phases']);

        $index = null;
        foreach ($phases as $i => $phase) {
            if ($phase['id'] === $phaseId) {
                $index = $i;
                break;
            }
        }

        abort_if($index === null, 404);

        $targetIndex = $direction === 'up' ? $index - 1 : $index + 1;

        if ($targetIndex < 0 || $targetIndex >= count($phases)) {
            return;
        }

        [$phases[$index], $phases[$targetIndex]] = [$phases[$targetIndex], $phases[$index]];

        foreach ($phases as $i => $_) {
            $phases[$i]['sort_order'] = $i + 1;
        }

        $roadmap['phases'] = $phases;
        $this->write($roadmap);
    }

    public function moveItem(int $itemId, string $direction): void
    {
        $roadmap = $this->read();
        $phaseIndex = null;
        $itemIndex = null;

        foreach ($roadmap['phases'] as $pIdx => $phase) {
            foreach ($phase['items'] as $iIdx => $item) {
                if ($item['id'] === $itemId) {
                    $phaseIndex = $pIdx;
                    $itemIndex = $iIdx;
                    break 2;
                }
            }
        }

        abort_if($phaseIndex === null, 404);

        $items = $this->sortItems($roadmap['phases'][$phaseIndex]['items']);

        $sortedIndex = null;
        foreach ($items as $i => $item) {
            if ($item['id'] === $itemId) {
                $sortedIndex = $i;
                break;
            }
        }

        $targetIndex = $direction === 'up' ? $sortedIndex - 1 : $sortedIndex + 1;

        if ($targetIndex < 0 || $targetIndex >= count($items)) {
            return;
        }

        [$items[$sortedIndex], $items[$targetIndex]] = [$items[$targetIndex], $items[$sortedIndex]];

        foreach ($items as $i => $_) {
            $items[$i]['sort_order'] = $i + 1;
        }

        $roadmap['phases'][$phaseIndex]['items'] = $items;
        $this->write($roadmap);
    }

    /**
     * @return array{phases: array<int, array{id: int, label: string, title: string, status: string, sort_order: int, is_visible: bool, items: array<int, array{id: int, title: string, sort_order: int, is_visible: bool}>}>}
     */
    private function read(): array
    {
        if (! Storage::disk('roadmap')->exists(self::Path)) {
            $this->write(['phases' => []]);
        }

        $decoded = json_decode(Storage::disk('roadmap')->get(self::Path), true, flags: JSON_THROW_ON_ERROR);

        return [
            'phases' => array_map(
                fn (array $phase): array => [
                    'id' => (int) $phase['id'],
                    'label' => (string) $phase['label'],
                    'title' => (string) $phase['title'],
                    'status' => (string) $phase['status'],
                    'sort_order' => (int) $phase['sort_order'],
                    'is_visible' => (bool) $phase['is_visible'],
                    'items' => array_map(
                        fn (array $item): array => [
                            'id' => (int) $item['id'],
                            'title' => (string) $item['title'],
                            'sort_order' => (int) $item['sort_order'],
                            'is_visible' => (bool) $item['is_visible'],
                        ],
                        Arr::get($phase, 'items', [])
                    ),
                ],
                Arr::get($decoded, 'phases', [])
            ),
        ];
    }

    /**
     * @param  array{phases: array<int, array<string, mixed>>}  $roadmap
     */
    private function write(array $roadmap): void
    {
        Storage::disk('roadmap')->put(
            self::Path,
            json_encode($roadmap, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR).PHP_EOL
        );
    }

    /**
     * @param  array<int, array{id: int, sort_order: int, items: array<int, array{id: int, sort_order: int}>}>  $phases
     * @return array<int, array{id: int, sort_order: int, items: array<int, array{id: int, sort_order: int}>}>
     */
    private function sortPhases(array $phases): array
    {
        return collect($phases)
            ->map(function (array $phase): array {
                $phase['items'] = $this->sortItems($phase['items']);

                return $phase;
            })
            ->sortBy([
                ['sort_order', 'asc'],
                ['id', 'asc'],
            ])
            ->values()
            ->all();
    }

    /**
     * @param  array<int, array{id: int, sort_order: int}>  $items
     * @return array<int, array{id: int, sort_order: int}>
     */
    private function sortItems(array $items): array
    {
        return collect($items)
            ->sortBy([
                ['sort_order', 'asc'],
                ['id', 'asc'],
            ])
            ->values()
            ->all();
    }

    /**
     * @param  array<int, array{id: int}>  $phases
     */
    private function nextPhaseId(array $phases): int
    {
        return ((int) collect($phases)->max('id')) + 1;
    }

    /**
     * @param  array<int, array{items: array<int, array{id: int}>}>  $phases
     */
    private function nextItemId(array $phases): int
    {
        return ((int) collect($phases)->flatMap(fn (array $phase): array => $phase['items'])->max('id')) + 1;
    }
}
