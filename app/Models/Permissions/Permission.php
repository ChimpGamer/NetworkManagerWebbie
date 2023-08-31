<?php

namespace App\Models\Permissions;

interface Permission
{

    function willExpire(): bool;

    function hasExpired(): bool;
}
