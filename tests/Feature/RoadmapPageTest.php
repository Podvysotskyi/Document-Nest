<?php

namespace Tests\Feature;

use App\Models\Roadmap\RoadmapItem;
use App\Models\Roadmap\RoadmapPhase;
use Tests\TestCase;

class RoadmapPageTest extends TestCase
{
    public function test_roadmap_page_is_publicly_accessible(): void
    {
        foreach ([
            'Private document vault',
            'Document intelligence and reminders',
            'Shared household workflows',
            'Automation and business use cases',
        ] as $index => $title) {
            RoadmapPhase::factory()->create([
                'title' => $title,
                'sort_order' => $index + 1,
                'is_visible' => true,
            ]);
        }

        $this->get(route('roadmap'))
            ->assertStatus(200)
            ->assertSee('Project roadmap')
            ->assertSee('Private document vault')
            ->assertSee('Document intelligence and reminders')
            ->assertSee('Shared household workflows')
            ->assertSee('Automation and business use cases');
    }

    public function test_roadmap_page_only_shows_visible_database_content(): void
    {
        RoadmapItem::query()->delete();
        RoadmapPhase::query()->delete();

        $visiblePhase = RoadmapPhase::factory()->create([
            'label' => 'Visible',
            'title' => 'Visible public phase',
            'status' => 'Published',
            'sort_order' => 1,
            'is_visible' => true,
        ]);
        RoadmapItem::factory()->for($visiblePhase, 'phase')->create([
            'title' => 'Visible public item',
            'sort_order' => 1,
            'is_visible' => true,
        ]);
        RoadmapItem::factory()->for($visiblePhase, 'phase')->create([
            'title' => 'Hidden item',
            'sort_order' => 2,
            'is_visible' => false,
        ]);

        $hiddenPhase = RoadmapPhase::factory()->create([
            'label' => 'Hidden',
            'title' => 'Hidden phase',
            'status' => 'Draft',
            'sort_order' => 2,
            'is_visible' => false,
        ]);
        RoadmapItem::factory()->for($hiddenPhase, 'phase')->create([
            'title' => 'Hidden phase item',
            'sort_order' => 1,
            'is_visible' => true,
        ]);

        $this->get(route('roadmap'))
            ->assertStatus(200)
            ->assertSee('Visible public phase')
            ->assertSee('Visible public item')
            ->assertDontSee('Hidden item')
            ->assertDontSee('Hidden phase')
            ->assertDontSee('Hidden phase item');
    }

    public function test_welcome_page_links_to_roadmap(): void
    {
        $this->get('/')
            ->assertStatus(200)
            ->assertSee(route('roadmap'), false)
            ->assertSee('View roadmap');
    }
}
