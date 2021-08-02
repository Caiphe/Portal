<?php

namespace App\Services\Kyc;

class KycService
{
    /**
     * Get's the next Kyc class if it exists
     *
     * @param      array  $groups  The groups
     */
    public function getNextKyc(array $groups)
    {
        foreach ($groups as $group) {
            $g = ucfirst(ucwords($group));
            $class = "\App\Services\Kyc\\{$g}Service";
            if (!class_exists($class)) continue;
            return new $class();
        }

        return null;
    }

    /**
     * Load a specific Kyc class
     *
     * @param      string  $group  The group
     */
    public function load(string $group)
    {
        $g = ucfirst(ucwords($group));
        $class = "\App\Services\Kyc\\{$g}Service";
        return new $class();
    }
}
