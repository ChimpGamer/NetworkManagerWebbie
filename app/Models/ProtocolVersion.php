<?php

namespace App\Models;

enum ProtocolVersion: int
{
    case MINECRAFT_1_7_5 = 4;
    case MINECRAFT_1_7_10 = 5;
    case MINECRAFT_1_8 = 47;
    case MINECRAFT_1_9 = 107;
    case MINECRAFT_1_9_1 = 108;
    case MINECRAFT_1_9_2 = 109;
    case MINECRAFT_1_9_4 = 110;
    case MINECRAFT_1_10 = 210;
    case MINECRAFT_1_11 = 315;
    case MINECRAFT_1_11_1 = 316;
    case MINECRAFT_1_12 = 335;
    case MINECRAFT_1_12_1 = 338;
    case MINECRAFT_1_12_2 = 340;
    case MINECRAFT_1_13 = 393;
    case MINECRAFT_1_13_1 = 401;
    case MINECRAFT_1_13_2 = 404;
    case MINECRAFT_1_14 = 447;
    case MINECRAFT_1_14_1 = 480;
    case MINECRAFT_1_14_2 = 485;
    case MINECRAFT_1_14_3 = 490;
    case MINECRAFT_1_14_4 = 498;
    case MINECRAFT_1_15 = 573;
    case MINECRAFT_1_15_1 = 575;
    case MINECRAFT_1_15_2 = 578;
    case MINECRAFT_1_16 = 735;
    case MINECRAFT_1_16_1 = 736;
    case MINECRAFT_1_16_2 = 750;
    case MINECRAFT_1_16_3 = 753;
    case MINECRAFT_1_16_4 = 754;
    case MINECRAFT_1_17 = 755;
    case MINECRAFT_1_17_1 = 756;
    case MINECRAFT_1_18 = 757;
    case MINECRAFT_1_18_2 = 758;
    case MINECRAFT_1_19 = 759;
    case MINECRAFT_1_19_1 = 760;
    case MINECRAFT_1_19_3 = 761;
    case MINECRAFT_1_19_4 = 762;
    case MINECRAFT_1_20 = 763;
    case SNAPSHOT = -1;

    public function name(): string
    {
        return self::getName($this);
    }

    public static function getName(self $version): string
    {
        return match ($version) {
            ProtocolVersion::MINECRAFT_1_7_5 => '1.7.2 - 1.7.5',
            ProtocolVersion::MINECRAFT_1_7_10 => '1.7.6 - 1.7.10',
            ProtocolVersion::MINECRAFT_1_8 => '1.8.x',
            ProtocolVersion::MINECRAFT_1_9 => '1.9',
            ProtocolVersion::MINECRAFT_1_9_1 => '1.9.1',
            ProtocolVersion::MINECRAFT_1_9_2 => '1.9.2',
            ProtocolVersion::MINECRAFT_1_9_4 => '1.9.3 - 1.9.4',
            ProtocolVersion::MINECRAFT_1_10 => '1.10.x',
            ProtocolVersion::MINECRAFT_1_11 => '1.11',
            ProtocolVersion::MINECRAFT_1_11_1 => '1.11.x',
            ProtocolVersion::MINECRAFT_1_12 => '1.12',
            ProtocolVersion::MINECRAFT_1_12_1 => '1.12.1',
            ProtocolVersion::MINECRAFT_1_12_2 => '1.12.2',
            ProtocolVersion::MINECRAFT_1_13 => '1.13',
            ProtocolVersion::MINECRAFT_1_13_1 => '1.13.1',
            ProtocolVersion::MINECRAFT_1_13_2 => '1.13.2',
            ProtocolVersion::MINECRAFT_1_14 => '1.14',
            ProtocolVersion::MINECRAFT_1_14_1 => '1.14.1',
            ProtocolVersion::MINECRAFT_1_14_2 => '1.14.2',
            ProtocolVersion::MINECRAFT_1_14_3 => '1.14.3',
            ProtocolVersion::MINECRAFT_1_14_4 => '1.14.4',
            ProtocolVersion::MINECRAFT_1_15 => '1.15',
            ProtocolVersion::MINECRAFT_1_15_1 => '1.15.1',
            ProtocolVersion::MINECRAFT_1_15_2 => '1.15.2',
            ProtocolVersion::MINECRAFT_1_16 => '1.16',
            ProtocolVersion::MINECRAFT_1_16_1 => '1.16.1',
            ProtocolVersion::MINECRAFT_1_16_2 => '1.16.2',
            ProtocolVersion::MINECRAFT_1_16_3 => '1.16.3',
            ProtocolVersion::MINECRAFT_1_16_4 => '1.16.4 - 1.16.5',
            ProtocolVersion::MINECRAFT_1_17 => '1.17',
            ProtocolVersion::MINECRAFT_1_17_1 => '1.17.1',
            ProtocolVersion::MINECRAFT_1_18 => '1.18',
            ProtocolVersion::MINECRAFT_1_18_2 => '1.18.2',
            ProtocolVersion::MINECRAFT_1_19 => '1.19',
            ProtocolVersion::MINECRAFT_1_19_1 => '1.19.1',
            ProtocolVersion::MINECRAFT_1_19_3 => '1.19.3',
            ProtocolVersion::MINECRAFT_1_19_4 => '1.19.4',
            ProtocolVersion::MINECRAFT_1_20 => '1.20 - 1.20.1',
            default => 'snapshot'
        };
    }
}
