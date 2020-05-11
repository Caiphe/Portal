<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ApigeeService;
use App\Bundle;

class SyncBundle extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:bundles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync the bundles from Apigee';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info("Getting bundles from Apigee");
        $bundles = ApigeeService::getBundles();

        $this->info("Start syncing bundles");
        foreach ($bundles['monetizationPackage'] as $bundle) {
            $this->info("Syncing {$bundle['displayName']}");

            $p = Bundle::updateOrCreate(
                ["bid" => $bundle['id']],
                [
                    "bid" => $bundle['id'],
                    "name" => $bundle['name'],
                    "display_name" => $bundle['displayName'],
                    "description" => $bundle['description'],
                ]
            );

            $p->products()->sync(array_column($bundle['product'], 'name'));
        }
    }
}
