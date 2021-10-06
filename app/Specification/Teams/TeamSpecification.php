<?php

namespace App\Specification\Teams;

use Illuminate\Database\Eloquent\Model;

/**
 * Interface TeamSpecification
 *
 * @package App\Specification\Teams
 */
interface TeamSpecification
{
    public function matches(Model $subject, array $invitees);

    public function asQuery($query, array $invitees);
}
