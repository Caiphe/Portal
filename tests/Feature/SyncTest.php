<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SyncTest extends TestCase
{
    use RefreshDatabase;


    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(['CountrySeeder']);
    }

    /** @test */
    public function can_sync_products()
    {
        $this->artisan('sync:products')
            ->expectsOutput('Start syncing products')
            ->assertExitCode(0);
    }

    /** @test */
    public function can_sync_bundles()
    {
        $this->artisan('sync:bundles')
            ->expectsOutput('Getting bundles from Apigee')
            ->expectsOutput('Start syncing bundles')
            ->assertExitCode(0);
    }

    /** @test */
    public function can_sync_apps()
    {
        $this->artisan('sync:apps')
            ->expectsOutput('Getting apps from Apigee')
            ->expectsOutput('Start syncing apps')
            ->assertExitCode(0);
    }
}
