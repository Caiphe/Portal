<?php

namespace App\Services\AppAccess\NoteHandler;

use App\Services\ApigeeService;

/**
 * Trait ApigeeNoteHandler
 *
 * @package App\Services\AppAccess\NoteHandler
 */
trait ApigeeNoteHandler
{
    /**
     * @var ApigeeService $apigeeService
     */
    protected $apigeeService;

    /**
     * @return ApigeeService
     */
    public function getApigeeService(): ApigeeService
    {
        return $this->apigeeService;
    }

    /**
     * @param ApigeeService $service
     */
    public function setApigeeService(ApigeeService $service)
    {
        $this->apigeeService = $service;
    }
}
