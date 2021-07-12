<?php

namespace App\Services\AppAccess;

use RuntimeException;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;
use App\Services\AppAccess\NoteHandler\NoteHandler;
use App\Services\AppAccess\NoteHandler\ApigeeNoteHandler;

/**
 * Class AppAccess
 *
 * @package App\Services\AppAccess
 */
class AppAccess
{
    use NoteHandler, ApigeeNoteHandler;

    /**
     * @param string $category
     * @param Model $subject
     */
    public function revokeAccess($data, $user, $category = "app", $options = [])
    {
        if ("app" === $category) {
            $this->revokeAppAccess($data, $user, $options);
        } elseif ("product" === $category) {
            $this->revokeProductAccess($data, $user);
        } else {
            throw new RuntimeException("Could not fulfil revoke access note update request.");
        }
    }

    /**
     * @param string $category
     * @param Model $subject
     */
    public function approveAccess($data, $user, $category = "app", $options = [])
    {
        if ("app" === $category) {
            $this->approveAppAccess($data, $user, $options);
        } elseif ("product" === $category) {
            $this->approveProductAccess($data, $user);
        } else {
            throw new RuntimeException("Could not fulfil approve access note update request.");
        }
    }

    /**
     * @param $data
     * @param $user
     */
    protected function revokeProductAccess($data, $user)
    {
        try
        {
            $this->logActivityNote($user, $data);
        } catch (Exception $exc) {
            Log::error($exc->getMessage());
        }
    }

    /**
     * @param $data
     * @param $user
     * @param $options
     */
    protected function revokeAppAccess($data, $user, $options)
    {
        try
        {
            $this->logActivityNote($user, $data);
            $this->apigeeService->pushAppNote($data, $user, $options);
        } catch (Exception $exc) {
            Log::error($exc->getMessage());
        } catch (ValidationException $e) {
            Log::error("Apigee note update failed validation for {$data['appName']}");
        }
    }

    /**
     * @param $data
     * @param $user
     */
    protected function approveProductAccess($data, $user)
    {
        try
        {
            $this->logActivityNote($user, $data);
        } catch (Exception $exc) {
            Log::error($exc->getMessage());
        }
    }

    /**
     * @param $data
     * @param $user
     * @param $options
     */
    protected function approveAppAccess($data, $user, $options)
    {
        try
        {
            $this->logActivityNote($user, $data);
            $this->apigeeService->pushAppNote($data, $user, $options);
        } catch (Exception $exc) {
            Log::error($exc->getMessage());
        } catch (ValidationException $e) {
            \Log::error("Apigee note update failed validation for {$data['appName']}");
        }
    }
}
