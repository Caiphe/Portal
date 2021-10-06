<?php

namespace App\Specification;

use Illuminate\Database\Eloquent\Model;

/**
 * Interface Specification
 *
 * @package App\Specification
 */
interface Specification
{
    public function matches(Model $subject);
}
