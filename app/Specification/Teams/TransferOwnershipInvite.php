<?php

namespace App\Specification\Teams;

use Illuminate\Database\Eloquent\Model;

class TransferOwnershipInvite implements InviteSpecification
{
    const TYPE_CHECK = 'ownership';

    public function matches(Model $subject)
    {
        if ($subject->type === static::TYPE_CHECK) {
            return true;
        } else {
            return false;
        }
    }

    public function asQuery($query)
    {
        return $query->where('type', static::TYPE_CHECK);
    }
}
