<?php

namespace Tests\Feature;

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

    public function test_welcome_page_links_to_roadmap(): void
    {
        $this->get('/')
            ->assertStatus(200)
            ->assertSee(route('roadmap'), false)
            ->assertSee('View roadmap');
    }
}
