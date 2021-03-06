<?php

namespace Spatie\Backup\Tests\HealthChecks;

use Spatie\Backup\Tests\TestCase;
use Illuminate\Support\Facades\Event;
use Spatie\Backup\Events\HealthyBackupWasFound;
use Spatie\Backup\Events\UnhealthyBackupWasFound;

class IsReachableTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        Event::fake();
    }

    /** @test */
    public function it_succeeds_when_destination_is_reachable()
    {
        $this->artisan('backup:monitor')->assertExitCode(0);

        Event::assertDispatched(HealthyBackupWasFound::class);
    }

    /** @test */
    public function it_fails_when_backup_destination_is_not_reachable()
    {
        config()->set('backup.monitor_backups.0.disks', ['nonExistingDisk']);

        $this->artisan('backup:monitor')->assertExitCode(0);

        Event::assertDispatched(UnhealthyBackupWasFound::class);
    }
}
