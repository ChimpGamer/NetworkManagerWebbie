<?php

namespace App\Models\Chat;

enum ChatType: int
{
    case CHAT = 1;
    case PM = 2;
    case PARTY = 3;
    case STAFFCHAT = 4;
    case ADMINCHAT = 5;
    case FRIENDS = 6;

    public function name(): string
    {
        return self::getName($this);
    }

    public static function getName(self $chatType): string
    {
        return match ($chatType) {
            ChatType::CHAT => 'Chat',
            ChatType::PM => 'PM',
            ChatType::PARTY => 'Party',
            ChatType::STAFFCHAT => 'Staff Chat',
            ChatType::ADMINCHAT => 'Admin Chat',
            ChatType::FRIENDS => 'Friends',
        };
    }
}
