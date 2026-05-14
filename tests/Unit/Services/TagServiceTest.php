<?php

namespace Tests\Unit\Services;

use App\DTOs\StoreTagData;
use App\DTOs\UpdateTagData;
use App\Models\Tag;
use App\Models\User;
use App\Services\TagService;
use Tests\TestCase;

class TagServiceTest extends TestCase
{
    public function test_tag_service_creates_tag_for_user_and_generates_unique_slug(): void
    {
        $user = User::factory()->create();
        $service = app(TagService::class);

        $first = $service->createForUser($user, new StoreTagData(name: 'Needs Review'));
        $second = $service->createForUser($user, new StoreTagData(name: 'Needs Review'));

        $this->assertSame('Needs Review', $first->name);
        $this->assertSame('needs-review', $first->slug);
        $this->assertSame('needs-review-2', $second->slug);
    }

    public function test_tag_service_updates_tag_and_keeps_slug_unique(): void
    {
        $user = User::factory()->create();
        $service = app(TagService::class);

        $existing = Tag::factory()->for($user)->create([
            'name' => 'Work Notes',
            'slug' => 'work-notes',
        ]);

        $tag = Tag::factory()->for($user)->create([
            'name' => 'Archive',
            'slug' => 'archive',
        ]);

        $updated = $service->update($tag, new UpdateTagData(name: $existing->name));

        $this->assertSame('Work Notes', $updated->name);
        $this->assertSame('work-notes-2', $updated->slug);
    }
}
