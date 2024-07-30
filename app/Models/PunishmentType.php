<?php

namespace App\Models;

enum PunishmentType: int
{
    case BAN = 1;
    case GBAN = 2;
    case TEMPBAN = 3;
    case GTEMPBAN = 4;
    case IPBAN = 5;
    case GIPBAN = 6;
    case TEMPIPBAN = 7;
    case GTEMPIPBAN = 8;
    case MUTE = 9;
    case GMUTE = 10;
    case TEMPMUTE = 11;
    case GTEMPMUTE = 12;
    case IPMUTE = 13;
    case GIPMUTE = 14;
    case TEMPIPMUTE = 15;
    case GTEMPIPMUTE = 16;
    case KICK = 17;
    case GKICK = 18;
    case WARN = 19;
    case REPORT = 20;
    case NOTE = 21;

    public function name(): string
    {
        return self::getName($this);
    }

    public function isGlobal(): bool
    {
        return match ($this) {
            PunishmentType::GBAN, PunishmentType::GTEMPBAN, PunishmentType::GIPBAN, PunishmentType::GTEMPIPBAN,
            PunishmentType::GMUTE, PunishmentType::GTEMPMUTE, PunishmentType::GIPMUTE, PunishmentType::GTEMPIPMUTE,
            PunishmentType::GKICK, PunishmentType::WARN, PunishmentType::REPORT, PunishmentType::NOTE => true,
            default => false,
        };
    }

    public function isTemporary(): bool
    {
        return match ($this) {
            PunishmentType::GTEMPBAN, PunishmentType::TEMPBAN, PunishmentType::GTEMPIPBAN, PunishmentType::TEMPIPBAN,
            PunishmentType::GTEMPMUTE, PunishmentType::GTEMPIPMUTE, PunishmentType::TEMPMUTE, PunishmentType::TEMPIPMUTE,
            PunishmentType::WARN => true,
            default => false
        };
    }

    public static function getName(self $type): string
    {
        return match ($type) {
            PunishmentType::BAN => 'Ban',
            PunishmentType::GBAN => 'Global Ban',
            PunishmentType::TEMPBAN => 'Temporary Ban',
            PunishmentType::GTEMPBAN => 'Global Temporary Ban',
            PunishmentType::IPBAN => 'IP Ban',
            PunishmentType::GIPBAN => 'Global IP Ban',
            PunishmentType::TEMPIPBAN => 'Temporary IP Ban',
            PunishmentType::GTEMPIPBAN => 'Global Temporary IP Ban',
            PunishmentType::MUTE => 'Mute',
            PunishmentType::GMUTE => 'Global Mute',
            PunishmentType::TEMPMUTE => 'Temporary Mute',
            PunishmentType::GTEMPMUTE => 'Global Temporary Mute',
            PunishmentType::IPMUTE => 'IP Mute',
            PunishmentType::GIPMUTE => 'Global IP Mute',
            PunishmentType::TEMPIPMUTE => 'Temporary IP Mute',
            PunishmentType::GTEMPIPMUTE => 'Global Temporary IP Mute',
            PunishmentType::KICK => 'Kick',
            PunishmentType::GKICK => 'Global Kick',
            PunishmentType::WARN => 'Warn',
            PunishmentType::REPORT => 'Report',
            PunishmentType::NOTE => 'Note',
            default => 'Unknown',
        };
    }
}
