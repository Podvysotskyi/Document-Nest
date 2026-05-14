<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RoadmapPageTest extends TestCase
{
    public function test_roadmap_page_is_publicly_accessible(): void
    {
        $this->get(route('roadmap'))
            ->assertStatus(200)
            ->assertSee('Project roadmap')
            ->assertSee('Private document vault')
            ->assertSee('Document intelligence and reminders')
            ->assertSee('Shared household workflows')
            ->assertSee('Automation and business use cases');
    }

    public function test_roadmap_page_only_shows_visible_json_content(): void
    {
        Storage::fake('roadmap');
        Storage::disk('roadmap')->put('data/roadmap.json', json_encode([
            'phases' => [
                [
                    'id' => 1,
                    'label' => 'Visible',
                    'title' => 'Visible public phase',
                    'status' => 'Published',
                    'sort_order' => 1,
                    'is_visible' => true,
                    'items' => [
                        [
                            'id' => 1,
                            'title' => 'Visible public item',
                            'sort_order' => 1,
                            'is_visible' => true,
                        ],
                        [
                            'id' => 2,
                            'title' => 'Hidden item',
                            'sort_order' => 2,
                            'is_visible' => false,
                        ],
                    ],
                ],
                [
                    'id' => 2,
                    'label' => 'Hidden',
                    'title' => 'Hidden phase',
                    'status' => 'Draft',
                    'sort_order' => 2,
                    'is_visible' => false,
                    'items' => [
                        [
                            'id' => 3,
                            'title' => 'Hidden phase item',
                            'sort_order' => 1,
                            'is_visible' => true,
                        ],
                    ],
                ],
            ],
        ], JSON_THROW_ON_ERROR));

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
