<?php

namespace App\Services\AppAccess\NoteHandler;

use App\AppsActivityLog;

/**
 * Trait NoteHandler
 *
 * @package App\Services\AppAccess\NoteHandler
 */
trait NoteHandler
{
    /**
     * @param $data
     * @param $user
     */
    public function logActivityNote($data, $user)
    {
        AppsActivityLog::create([
            'aid' =>$data['aid'],
            'user_id' => $user->id,
            'status' => $data['status'],
            'comment' => $data['note']
        ]);
    }
}
