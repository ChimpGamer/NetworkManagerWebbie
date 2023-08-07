<?php

namespace App\Models;

enum AnnouncementType: int
{
    case CHATALLSERVERS = 1;
    case CHATSERVERSONLY = 2;
    case CHATSERVERSEXCEPT = 3;
    case ACTIONBARALLSERVERS = 4;
    case ACTIONBARSERVERSONLY = 5;
    case ACTIONBARSERVERSEXCEPT = 6;
    case TITLEALLSERVERS = 7;
    case TITLESERVERSONLY = 8;
    case TITLESERVERSEXCEPT = 9;

    public function name(): string
    {
        return self::getName($this);
    }

    public static function getName(self $type): string
    {
        return match ($type) {
            AnnouncementType::CHATALLSERVERS => 'Chat All Servers',
            AnnouncementType::CHATSERVERSONLY => 'Chat Servers Only',
            AnnouncementType::CHATSERVERSEXCEPT => 'Chat Servers Except',
            AnnouncementType::ACTIONBARALLSERVERS => 'ActionBar All Servers',
            AnnouncementType::ACTIONBARSERVERSONLY => 'Action Servers Only',
            AnnouncementType::ACTIONBARSERVERSEXCEPT => 'Action Servers Except',
            AnnouncementType::TITLEALLSERVERS => 'Title All Servers',
            AnnouncementType::TITLESERVERSONLY => 'Title Servers Only',
            AnnouncementType::TITLESERVERSEXCEPT => 'Title Servers Except'
        };
    }
}
