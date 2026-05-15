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
            ->assertSee('View roadmap')
            ->assertSee(route('about'), false)
            ->assertSee(route('license'), false);
    }

    public function test_about_page_is_publicly_accessible(): void
    {
        $this->get(route('about'))
            ->assertStatus(200)
            ->assertSee('About Document Nest')
            ->assertSee('Privacy first');
    }

    public function test_license_page_is_publicly_accessible(): void
    {
        $this->get(route('license'))
            ->assertStatus(200)
            ->assertSee('License')
            ->assertSee('GNU General Public License, Version 3')
            ->assertSee('TERMS AND CONDITIONS')
            ->assertSee('END OF TERMS AND CONDITIONS');
    }
}
