<?php

namespace App\Services\AppAccess;

use App\AppsActivityLog;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
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
     * @var Request $request
     */
    protected $request;

    /**
     * @var Model $appsActivityLog
     */
    private $appsActivityLog;

    /**
     * AppAccess constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;

        $this->appsActivityLog = new AppsActivityLog();
    }

    /**
     * @param string $category
     * @param Model $subject
     */
    public function revokeAccess(string $category, Model $subject)
    {
        if ("product" === $category) {
            $this->revokeAppAccess($subject);
        } elseif ("app" === $category) {
            $this->revokeProductAccess($subject);
        } else {
            throw new \RuntimeException("Could not fulfil request.");
        }
    }

    /**
     * @param string $category
     * @param Model $subject
     */
    public function approveAccess(string $category, Model $subject)
    {
        if ("product" === $category) {
            $this->approveAppAccess($subject);
        } elseif ("app" === $category) {
            $this->approveProductAccess($subject);
        } else {
            throw new \RuntimeException("Could not fulfil request.");
        }
    }

    protected function revokeProductAccess($subject)
    {
        // Trigger event to Approve an App Access, if needs be, may include an Email Sending Listener

            // How do we handle approving and revoking of App Access?

        // Add Note to local database storage
    }

    protected function revokeAppAccess($subject)
    {
        // Trigger event to Approve an App Access, if needs be, may include an Email Sending Listener

            // How do we handle approving and revoking of App Access?

        // Add Note to local database storage

        // Push Note to Apigee
    }

    protected function approveProductAccess($subject)
    {
        // Trigger event to Approve an App Access, if needs be, may include an Email Sending Listener

            // How do we handle approving and revoking of App Access?

        // Add Note to local database storage
    }

    protected function approveAppAccess($subject)
    {
        // Trigger event to Approve an App Access, if needs be, may include an Email Sending Listener

            // How do we handle approving and revoking of App Access?

        // Add Note to local database storage

        // Push Note to Apigee
    }
}
