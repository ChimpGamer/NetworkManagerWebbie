<?php

namespace App\Models\Permissions;

interface Permission
{
    public function willExpire(): bool;

    public function hasExpired(): bool;
}
