<?php

return [
    'players' => [
        'title' => 'Players',
        'table' => [
            'columns' => [
                'player' => 'Player',
                'first-login' => 'First Login',
                'last-login' => 'Last Login',
                'online' => 'Online',
            ],
        ],
    ],
    'player' => [
        'title' => 'Player',
        'information' => [
            'title' => 'Player Information',
            'username' => 'Username',
            'nickname' => 'Nickname',
            'uuid' => 'UUID',
            'latest-minecraft-version' => 'Latest Minecraft Version',
            'ip-address' => 'IP Address',
            'country' => 'Country',
            'tag' => 'Tag',
            'first-login' => 'First Login',
            'last-login' => 'Last Login',
            'last-logout' => 'Last Logout',
            'playtime' => 'Playtime',
            'online' => 'Online',
        ],
        'statistics' => [
            'title' => 'Player Statistics',
            'average-playtime' => 'Avg Playtime',
            'normally-joins-at' => 'Normally Join at',
            'additional-accounts' => 'Additional Accounts',
        ],
        'sessions' => [
            'title' => 'Player Sessions',
            'table' => [
                'columns' => [
                    'start' => 'Start',
                    'end' => 'End',
                    'time' => 'Time',
                    'ip' => 'IP',
                    'version' => 'Version',
                ],
            ],
        ],
        'punishments' => [
            'title' => 'Player Punishments',
            'table' => [
                'columns' => [
                    'type' => 'Type',
                    'punisher' => 'Punisher',
                    'reason' => 'Reason',
                    'time' => 'Time',
                ],
            ],
        ],
        'ignored-players' => [
            'title' => 'Ignored Players',
            'table' => [
                'columns' => [
                    'ignored-player' => 'Ignored Player',
                ]
            ]
        ],
    ],
];
