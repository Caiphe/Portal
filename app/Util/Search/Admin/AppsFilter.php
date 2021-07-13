<?php

namespace App\Util\Search\Admin;

/**
 * Trait AdminAppsFilter
 *
 * @package App\Util\Search
 */
trait AppsFilter
{
    /**
     * The email that belongs to the Developer linked with the Apps searching for
     *
     * @var $developerFilterEmail
     */
    private $developerFilterEmail;

    /**
     * User selected status(es) to filter with (excluding all and pending)
     *
     * @var array $selectedStatuses
     */
    private $selectedStatuses = [];

    /**
     * A list of supported statuses at persistence level
     *
     * @var string[] $supportedStatuses
     */
    private $supportedStatuses = [ 'approved', 'revoked' ];

    /**
     * Check if it is an E-mail, and if it is; join the Apps with the Users
     * table through the Developer ID - otherwise search term is App name
     *
     * @param $givenInputString
     * @return bool
     */
    public function hasDeveloperFilter($givenInputString): bool
    {
        if (preg_match('/\b[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b/si',$givenInputString)) {
            $this->developerFilterEmail = $givenInputString;
            return true;
        } else {
            return false;
        }
    }

    /**
     * Developer Email Address we need to filter with
     *
     * @return mixed
     */
    public function getDeveloperFilter()
    {
        return $this->developerFilterEmail;
    }

    /**
     * Whether has statuses to filter with given selection
     *
     * @param array $selected
     * @return array|false
     */
    public function hasFilterStatuses(array $selected)
    {
        if (!empty($sanitized = $this->sanitizeStatuses($selected))) {
            $this->setSelectedStatuses($sanitized);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Return a list of supported statuses by the QueryBuilder
     *
     * @return array
     */
    public function getSelectedStatuses(): array
    {
        return $this->selectedStatuses;
    }

    /**
     * Sanitize incoming filter statuses
     *
     * @param $selected
     * @return mixed
     */
    public function sanitizeStatuses(&$selected)
    {
        $this->unsetStatusAll($selected);
        $this->unsetPendingStatus($selected);

        return $selected;
    }

    /**
     * Unset the {all} status from incoming query parameters
     *
     * @param $selected
     */
    public function unsetStatusAll($selected)
    {
        if (isset($selected['all'])) {
            unset($selected['all']);
        }
    }

    /**
     * Unset the {pending} status from incoming query parameters
     *
     * @param $selected
     */
    public function unsetPendingStatus($selected)
    {
        if (isset($selected['pending'])) {
            unset($selected['pending']);
        }
    }

    /**
     * Set selected statuses from the Request
     *
     * @param array $selected
     */
    private function setSelectedStatuses(array $selected)
    {
        $this->selectedStatuses = $selected;
    }
}
