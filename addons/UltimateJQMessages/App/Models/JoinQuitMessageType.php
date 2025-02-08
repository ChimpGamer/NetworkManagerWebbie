<?php

namespace Addons\UltimateJQMessages\App\Models;

enum JoinQuitMessageType: int
{
    case JOIN = 0;
    case QUIT = 1;

    public function name(): string
    {
        return self::getName($this);
    }

    public static function getName(self $joinQuitMessageType): string
    {
        return match ($joinQuitMessageType) {
            JoinQuitMessageType::JOIN => 'Join',
            JoinQuitMessageType::QUIT => 'Quit',
        };
    }
}
