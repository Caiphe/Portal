<?php

namespace App\Specification\Teams;

use App\Specification\Specification;

/**
 * Interface InviteSpecification
 *
 * @package App\Specification\Teams
 */
interface InviteSpecification extends Specification
{
    public function asQuery($query);
}
