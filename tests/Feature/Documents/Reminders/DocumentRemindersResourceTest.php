<?php

namespace Tests\Feature\Documents\Reminders;

use App\Enums\DocumentStatus;
use App\Models\Document;
use App\Models\DocumentReminder;
use App\Models\User;
use Illuminate\Support\Carbon;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class DocumentRemindersResourceTest extends TestCase
{
    public function test_show_exposes_upcoming_reminders_and_last_sent_timestamp(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-01-01 09:00:00'));

        $user = User::factory()->create();
        $document = Document::factory()->for($user)->create([
            'expiry_date' => '2026-04-01',
            'status' => DocumentStatus::Active,
        ]);

        $upcoming = DocumentReminder::factory()
            ->for($document)
            ->for($user)
            ->dueOn('2026-03-25')
            ->create();
        $sent = DocumentReminder::factory()
            ->for($document)
            ->for($user)
            ->sent()
            ->dueOn('2026-03-02')
            ->create(['sent_at' => Carbon::parse('2026-03-02 07:05:00')]);

        $this->actingAs($user)
            ->get(route('documents.show', $document))
            ->assertInertia(fn (Assert $page) => $page
                ->component('Documents/Show')
                ->has('document.reminders.upcoming', 1)
                ->where('document.reminders.upcoming.0.id', $upcoming->id)
                ->where('document.reminders.upcoming.0.remind_on', '2026-03-25')
                ->where('document.reminders.last_sent_at', $sent->fresh()->sent_at->toIso8601String())
            );

        Carbon::setTestNow();
    }

    public function test_show_returns_empty_reminders_summary_when_none_exist(): void
    {
        $user = User::factory()->create();
        $document = Document::factory()->for($user)->create(['expiry_date' => null]);

        $this->actingAs($user)
            ->get(route('documents.show', $document))
            ->assertInertia(fn (Assert $page) => $page
                ->component('Documents/Show')
                ->has('document.reminders.upcoming', 0)
                ->where('document.reminders.last_sent_at', null)
            );
    }
}
