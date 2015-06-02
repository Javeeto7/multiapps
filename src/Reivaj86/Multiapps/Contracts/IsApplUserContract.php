<?php

namespace Reivaj86\Multiapps\Contracts;

interface IsApplUserContract
{
    /**
     * User belongs to many apps.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function appls();

    /**
     * Get all Apps as collection.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAppls();

    /**
     * Check if the user can use an app or apps.
     *
     * @param int|string|array $app
     * @param string $methodName
     * @return bool
     * @throws \Reivaj86\Multiapps\Exceptions\InvalidArgumentException
     */
    public function uses($appl, $methodName = 'One');

    /**
     * Attach App to a user.
     *
     * @param int|\Reivaj86\Multiapps\Models\Role $role
     * @return null|bool
     */
    public function attachApp($appl);

    /**
     * Detach app from a user.
     *
     * @param int|\Reivaj86\Multiapps\Models\Role $role
     * @return int
     */
    public function detachApp($appl);

    /**
     * Detach all apps from a user. //Banned action
     *
     * @return int
     */
    public function detachAllApps();

    /**
     * Get app level of a user.
     *
     * @param string $appl
     * @return int
     * @throws \Reivaj86\Multiapps\Exceptions\RoleNotFoundException
     */
    public function level($appl);

    /**
     * Get max appls level for a comon user. //Should always be 1. Admin level > 1
     *
     * @return int
     * @throws \Reivaj86\Multiapps\Exceptions\RoleNotFoundException
     */
    public function levelMax();

    /**
     * Check if the user is allowed to manipulate with entity.//Activated or Blocked action
     *
     * @param string $providedApp
     * @param object $entity
     * @param bool $owner
     * @param string $ownerColumn
     * @return bool
     */
    public function allowed($providedApp, $entity, $owner = true, $ownerColumn = 'user_id');


}
