<?php

declare(strict_types=1);

namespace YoAuth\Access;

use YoAuth\Access\Checkers\PermissionChecker;
use YoAuth\Access\Checkers\RoleChecker;
use YoAuth\Access\Checkers\TeamChecker;

class AccessManager
{
    private PermissionChecker $permissionChecker;
    private RoleChecker $roleChecker;
    private TeamChecker $teamChecker;

    public function __construct(PermissionChecker $permissionChecker, RoleChecker $roleChecker, TeamChecker $teamChecker)
    {
        $this->permissionChecker = $permissionChecker;
        $this->roleChecker       = $roleChecker;
        $this->teamChecker       = $teamChecker;
    }

    public function roles() : RoleChecker
    {
        return $this->roleChecker;
    }

    public function teams() : TeamChecker
    {
        return $this->teamChecker;
    }

    public function permission() : permissionChecker
    {
        return $this->permissionChecker;
    }
}
