<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ApigeeService;
use App\Bundle;
use App\Product;

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
        $allow = config('apigee.apigee_allow_prefix');
        $deny = explode(',', config('apigee.apigee_deny_prefix'));

        $this->info("Start syncing bundles");
        
        if(is_null($bundles)) return 0;

        foreach ($bundles['monetizationPackage'] as $bundle) {
            if ($allow !== "" && strpos($bundle['displayName'], $allow) === false) continue;
            if (str_replace($deny, '', $bundle['displayName']) !== $bundle['displayName']) continue;

            $this->info("Syncing {$bundle['displayName']}");

            $product = Product::find($bundle['product'][0]['id']);

            if(!$product){
                $this->warn("Please sync the {$bundle['displayName']} products first.");
                continue;
            }

            $p = Bundle::withTrashed()->updateOrCreate(
                ["bid" => $bundle['id']],
                [
                    "bid" => $bundle['id'],
                    "name" => $bundle['name'],
                    "display_name" => preg_replace('/[-_]+/', ' ', ltrim($bundle['displayName'], "$allow ")),
                    "description" => $bundle['description'],
                ]
            );

            $p->products()->sync(array_column($bundle['product'], 'name'));
        }

        return 0;
    }
}
