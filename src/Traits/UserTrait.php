<?php

declare(strict_types=1);

namespace YoAuth\Traits;

use YoAuth\Access\AccessManager;
use YoAuth\Models\Permission;
use YoAuth\Models\Role;
use YoAuth\Models\Team;

trait UserTrait
{
    /**
     * The instance of the AccessManager is a singleton
     * to maintain ease of use throughout an entire runtime session.
     *
     * @var AccessManager|null
     */
    private static ?AccessManager $accessManager = null;

    /**
     * This method will return an instantiated instance of the AccessManager.
     * If there's no active instance at any given moment, it will make one
     * and deposit it's value in the static variable.
     *
     * @return mixed|AccessManager|null
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function accessManager(): AccessManager
    {
        if (! self::$accessManager instanceof AccessManager) {
            self::$accessManager = app()->make(AccessManager::class);
        }

        return self::$accessManager;
    }

    /**
     * Returns all roles for the current user.
     *
     * @return array<Role>
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function roles(): array
    {
        return $this->accessManager()->roles()->getForCurrentUser();
    }

    /**
     * Checks if the current user has a certain role in a given team.
     * If no team is given, it'll resolve to the `team();` method in this trait.
     *
     * @param string $role
     * @param Team $team
     * @return bool
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function hasRole(string $role, ?Team $team = null): bool
    {
        return $this->accessManager()->roles()->currentUserHas($role, $team);
    }

    /**
     * @see hasRole
     *
     * @param string $role
     * @param Team $team
     * @return bool
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function isA(string $role, ?Team $team = null): Bool
    {
        return $this->hasRole($role, $team);
    }

    /**
     * @see hasRole
     *
     * @param string $role
     * @param Team $team
     * @return bool
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function isAn(string $role, ?Team $team = null): bool
    {
        return $this->hasRole($role, $team);
    }

    /**
     * Returns all permissions for the current user.
     *
     * @return array<Permission>
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function permissions(): array
    {
        return $this->accessManager()->permission()->getForCurrentUser();
    }

    /**
     * Checks if the current user has a certain permission in a given team.
     * If no team is given, it'll resolve to the `team();` method in this trait.
     *
     * @param string $permission
     * @param Team $team
     * @return bool
     */
    public function hasPermission(string $permission, ?Team $team = null): bool
    {
        return $this->accessManager()->permission()->currentUserHas($permission, $team);
    }

    /**
     * @see hasPermission
     *
     * @param string $role
     * @param Team|null $team
     * @return bool
     */
    public function can(string $role, ?Team $team = null): bool
    {
        return $this->hasPermission($role, $team);
    }

    /**
     * @see hasPermission
     *
     * @param string $role
     * @param Team|null $team
     * @return bool
     */
    public function isAbleTo(string $role, ?Team $team = null): bool
    {
        return $this->hasPermission($role, $team);
    }

    /**
     * Returns all teams that the current user is a part of.
     *
     * @return array<Team>
     */
    public function teams(): array
    {
        return $this->accessManager()->teams()->getForCurrentUser();
    }

    /**
     * Temporarily returns the first team.
     *
     * @return Team|null
     */
    public function team(): ?Team
    {
        // Temporary.
        $allTeams = $this->teams();
        return $allTeams[0] ?? null;
    }
}
