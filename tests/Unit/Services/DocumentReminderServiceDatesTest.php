<?php

namespace Tests\Unit\Services;

use App\Services\DocumentReminderService;
use Illuminate\Support\Carbon;
use PHPUnit\Framework\TestCase;

class DocumentReminderServiceDatesTest extends TestCase
{
    public function test_returns_empty_array_when_expiry_is_null(): void
    {
        $service = new DocumentReminderService;

        $this->assertSame([], $service->reminderDatesFor(null, Carbon::parse('2026-01-01')));
    }

    public function test_returns_empty_array_when_expiry_is_in_the_past(): void
    {
        $service = new DocumentReminderService;

        $this->assertSame([], $service->reminderDatesFor(Carbon::parse('2025-12-01'), Carbon::parse('2026-01-01')));
    }

    public function test_returns_three_lead_dates_when_expiry_is_far_in_the_future(): void
    {
        $service = new DocumentReminderService;

        $dates = $service->reminderDatesFor(Carbon::parse('2026-04-01'), Carbon::parse('2026-01-01'));

        $this->assertSame(['2026-03-02', '2026-03-25', '2026-03-31'], $dates);
    }

    public function test_skips_lead_dates_that_fall_on_or_before_today(): void
    {
        $service = new DocumentReminderService;

        // Expiry is in 10 days; 30-day reminder is in the past, 7-day and 1-day remain.
        $dates = $service->reminderDatesFor(Carbon::parse('2026-01-11'), Carbon::parse('2026-01-01'));

        $this->assertSame(['2026-01-04', '2026-01-10'], $dates);
    }

    public function test_keeps_lead_dates_that_land_on_today_so_the_next_scheduler_run_picks_them_up(): void
    {
        $service = new DocumentReminderService;

        // Expiry tomorrow → only the 1-day reminder lands on today; longer lead times are already in the past.
        $dates = $service->reminderDatesFor(Carbon::parse('2026-01-02'), Carbon::parse('2026-01-01'));

        $this->assertSame(['2026-01-01'], $dates);
    }
}
