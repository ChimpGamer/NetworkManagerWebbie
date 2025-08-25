<?php

return [

    /*
    |--------------------------------------------------------------------------
    | OAuth System Control
    |--------------------------------------------------------------------------
    |
    | Global OAuth system enable/disable and individual provider controls
    |
    */

    /*
    | Enable/disable the entire OAuth system
    */
    'enabled' => env('OAUTH_ENABLED', true),

    /*
    | Individual OAuth provider controls
    */
    'providers' => [
        'google' => [
            'enabled' => env('OAUTH_GOOGLE_ENABLED', true),
        ],
        'github' => [
            'enabled' => env('OAUTH_GITHUB_ENABLED', true),
        ],
        'discord' => [
            'enabled' => env('OAUTH_DISCORD_ENABLED', true),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | OAuth Registration Control
    |--------------------------------------------------------------------------
    |
    | These settings control who can register via OAuth providers.
    | You can enable multiple restriction methods simultaneously.
    |
    */

    'registration' => [
        /*
        | Enable/disable OAuth registration entirely
        */
        'enabled' => env('OAUTH_REGISTRATION_ENABLED', true),

        /*
        | Restriction mode: 'open', 'whitelist', 'invite_only', 'admin_approval'
        | - open: Anyone can register (default)
        | - whitelist: Only specific email domains allowed
        | - invite_only: Only users with valid invite codes
        | - admin_approval: Accounts require admin approval after creation
        */
        'mode' => env('OAUTH_REGISTRATION_MODE', 'open'),

        /*
        | Email domain whitelist
        | Only users with emails from these domains can register
        | Example: ['company.com', 'organization.org']
        */
        'allowed_domains' => explode(',', env('OAUTH_ALLOWED_DOMAINS', '')),

        /*
        | Invite system settings
        */
        'invites' => [
            'enabled' => env('OAUTH_INVITES_ENABLED', false),
            'expire_days' => env('OAUTH_INVITE_EXPIRE_DAYS', 7),
            'single_use' => env('OAUTH_INVITE_SINGLE_USE', true),
        ],

        /*
        | Admin approval settings
        */
        'admin_approval' => [
            'enabled' => env('OAUTH_ADMIN_APPROVAL_ENABLED', false),
            'default_active' => false, // New accounts start as inactive
            'notify_admins' => env('OAUTH_NOTIFY_ADMINS', true),
        ],

        /*
        | Blocked email addresses or domains
        | Users with these emails/domains cannot register
        */
        'blocked_domains' => explode(',', env('OAUTH_BLOCKED_DOMAINS', 'tempmail.com,10minutemail.com')),
        'blocked_emails' => explode(',', env('OAUTH_BLOCKED_EMAILS', '')),
    ],

    /*
    |--------------------------------------------------------------------------
    | OAuth Security Settings
    |--------------------------------------------------------------------------
    */

    'security' => [
        /*
        | Require email verification for OAuth accounts
        */
        'require_email_verification' => env('OAUTH_REQUIRE_EMAIL_VERIFICATION', false),

        /*
        | Maximum OAuth accounts per email address
        */
        'max_accounts_per_email' => env('OAUTH_MAX_ACCOUNTS_PER_EMAIL', 1),

        /*
        | Rate limiting for OAuth registration attempts
        */
        'rate_limit' => [
            'enabled' => env('OAUTH_RATE_LIMIT_ENABLED', true),
            'max_attempts' => env('OAUTH_RATE_LIMIT_ATTEMPTS', 5),
            'decay_minutes' => env('OAUTH_RATE_LIMIT_DECAY', 60),
        ],
    ],

];