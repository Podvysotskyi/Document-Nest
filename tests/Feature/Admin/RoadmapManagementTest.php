<?php

namespace Tests\Feature\Admin;

use App\Models\Roadmap\RoadmapItem;
use App\Models\Roadmap\RoadmapPhase;
use App\Models\User;
use App\Services\RoadmapService;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class RoadmapManagementTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->storeRoadmap([
            [
                'id' => 1,
                'label' => 'Now',
                'title' => 'Visible phase',
                'status' => 'Published',
                'sort_order' => 1,
                'is_visible' => true,
                'items' => [
                    [
                        'id' => 1,
                        'title' => 'Visible item',
                        'sort_order' => 1,
                        'is_visible' => true,
                    ],
                ],
            ],
        ]);
    }

    public function test_non_admin_user_cannot_view_roadmap_management(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.roadmap.index'))
            ->assertForbidden();
    }

    public function test_admin_user_cannot_view_roadmap_management_in_production(): void
    {
        $this->app->detectEnvironment(fn (): string => 'production');
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get(route('admin.roadmap.index'))
            ->assertNotFound();
    }

    public function test_admin_user_can_view_roadmap_management(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get(route('admin.roadmap.index'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Admin/Roadmap/Index')
                ->has('phases', 1)
                ->where('phases.0.title', 'Visible phase')
                ->where('phases.0.items.0.title', 'Visible item')
            );
    }

    public function test_admin_user_can_create_update_and_delete_a_roadmap_phase(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->post(route('admin.roadmap.phases.store'), [
                'label' => 'Next',
                'title' => 'New roadmap phase',
                'status' => 'Planned',
                'sort_order' => 10,
                'is_visible' => true,
            ])
            ->assertRedirect(route('admin.roadmap.index'));

        $phase = collect($this->roadmap()['phases'])->firstWhere('title', 'New roadmap phase');
        $this->assertIsArray($phase);

        $this->actingAs($admin)
            ->patch(route('admin.roadmap.phases.update', $phase['id']), [
                'label' => 'Later',
                'title' => 'Updated roadmap phase',
                'status' => 'Exploring',
                'sort_order' => 20,
                'is_visible' => false,
            ])
            ->assertRedirect(route('admin.roadmap.index'));

        $updatedPhase = collect($this->roadmap()['phases'])->firstWhere('id', $phase['id']);

        $this->assertSame('Later', $updatedPhase['label']);
        $this->assertSame('Updated roadmap phase', $updatedPhase['title']);
        $this->assertSame('Exploring', $updatedPhase['status']);
        $this->assertSame(20, $updatedPhase['sort_order']);
        $this->assertFalse($updatedPhase['is_visible']);

        $this->actingAs($admin)
            ->delete(route('admin.roadmap.phases.destroy', $phase['id']))
            ->assertRedirect(route('admin.roadmap.index'));

        $this->assertNull(collect($this->roadmap()['phases'])->firstWhere('id', $phase['id']));
    }

    public function test_admin_user_can_create_update_and_delete_a_roadmap_item(): void
    {
        $admin = User::factory()->admin()->create();
        $phase = $this->roadmap()['phases'][0];

        $this->actingAs($admin)
            ->post(route('admin.roadmap.items.store', $phase['id']), [
                'title' => 'New roadmap item',
                'sort_order' => 5,
                'is_visible' => true,
            ])
            ->assertRedirect(route('admin.roadmap.index'));

        $item = collect($this->roadmap()['phases'][0]['items'])->firstWhere('title', 'New roadmap item');
        $this->assertIsArray($item);

        $this->actingAs($admin)
            ->patch(route('admin.roadmap.items.update', $item['id']), [
                'title' => 'Updated roadmap item',
                'sort_order' => 6,
                'is_visible' => false,
            ])
            ->assertRedirect(route('admin.roadmap.index'));

        $updatedItem = collect($this->roadmap()['phases'][0]['items'])->firstWhere('id', $item['id']);

        $this->assertSame('Updated roadmap item', $updatedItem['title']);
        $this->assertSame(6, $updatedItem['sort_order']);
        $this->assertFalse($updatedItem['is_visible']);

        $this->actingAs($admin)
            ->delete(route('admin.roadmap.items.destroy', $item['id']))
            ->assertRedirect(route('admin.roadmap.index'));

        $this->assertNull(collect($this->roadmap()['phases'][0]['items'])->firstWhere('id', $item['id']));
    }

    public function test_admin_user_can_move_a_roadmap_phase_up_and_down(): void
    {
        $admin = User::factory()->admin()->create();

        $this->storeRoadmap([
            ['id' => 1, 'label' => 'A', 'title' => 'First', 'status' => 'Now', 'sort_order' => 1, 'is_visible' => true, 'items' => []],
            ['id' => 2, 'label' => 'B', 'title' => 'Second', 'status' => 'Next', 'sort_order' => 2, 'is_visible' => true, 'items' => []],
            ['id' => 3, 'label' => 'C', 'title' => 'Third', 'status' => 'Later', 'sort_order' => 3, 'is_visible' => true, 'items' => []],
        ]);

        $this->actingAs($admin)
            ->post(route('admin.roadmap.phases.move', 3), ['direction' => 'up'])
            ->assertRedirect(route('admin.roadmap.index'));

        $phases = collect($this->roadmap()['phases'])->sortBy('sort_order')->values()->all();
        $this->assertSame([1, 3, 2], array_column($phases, 'id'));
        $this->assertSame([1, 2, 3], array_column($phases, 'sort_order'));

        $this->actingAs($admin)
            ->post(route('admin.roadmap.phases.move', 1), ['direction' => 'down'])
            ->assertRedirect(route('admin.roadmap.index'));

        $phases = collect($this->roadmap()['phases'])->sortBy('sort_order')->values()->all();
        $this->assertSame([3, 1, 2], array_column($phases, 'id'));
        $this->assertSame([1, 2, 3], array_column($phases, 'sort_order'));
    }

    public function test_moving_a_phase_at_boundary_is_a_no_op(): void
    {
        $admin = User::factory()->admin()->create();

        $this->storeRoadmap([
            ['id' => 1, 'label' => 'A', 'title' => 'First', 'status' => 'Now', 'sort_order' => 1, 'is_visible' => true, 'items' => []],
            ['id' => 2, 'label' => 'B', 'title' => 'Second', 'status' => 'Next', 'sort_order' => 2, 'is_visible' => true, 'items' => []],
        ]);

        $this->actingAs($admin)
            ->post(route('admin.roadmap.phases.move', 1), ['direction' => 'up'])
            ->assertRedirect(route('admin.roadmap.index'));

        $phases = collect($this->roadmap()['phases'])->sortBy('sort_order')->values()->all();
        $this->assertSame([1, 2], array_column($phases, 'id'));
    }

    public function test_admin_user_can_move_a_roadmap_item_up_and_down(): void
    {
        $admin = User::factory()->admin()->create();

        $this->storeRoadmap([
            [
                'id' => 1, 'label' => 'A', 'title' => 'Phase', 'status' => 'Now', 'sort_order' => 1, 'is_visible' => true,
                'items' => [
                    ['id' => 10, 'title' => 'First',  'sort_order' => 1, 'is_visible' => true],
                    ['id' => 11, 'title' => 'Second', 'sort_order' => 2, 'is_visible' => true],
                    ['id' => 12, 'title' => 'Third',  'sort_order' => 3, 'is_visible' => true],
                ],
            ],
        ]);

        $this->actingAs($admin)
            ->post(route('admin.roadmap.items.move', 12), ['direction' => 'up'])
            ->assertRedirect(route('admin.roadmap.index'));

        $items = collect($this->roadmap()['phases'][0]['items'])->sortBy('sort_order')->values()->all();
        $this->assertSame([10, 12, 11], array_column($items, 'id'));
        $this->assertSame([1, 2, 3], array_column($items, 'sort_order'));
    }

    public function test_move_request_rejects_invalid_direction(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->post(route('admin.roadmap.phases.move', 1), ['direction' => 'sideways'])
            ->assertSessionHasErrors('direction');
    }

    /**
     * @param  array<int, array<string, mixed>>  $phases
     */
    private function storeRoadmap(array $phases): void
    {
        RoadmapItem::query()->delete();
        RoadmapPhase::query()->delete();

        foreach ($phases as $phaseData) {
            $items = $phaseData['items'];
            unset($phaseData['items']);

            $phase = RoadmapPhase::factory()->create($phaseData);

            foreach ($items as $itemData) {
                RoadmapItem::factory()->for($phase, 'phase')->create($itemData);
            }
        }
    }

    /**
     * @return array{phases: array<int, array<string, mixed>>}
     */
    private function roadmap(): array
    {
        return [
            'phases' => app(RoadmapService::class)->allPhasesForAdmin(),
        ];
    }
}
