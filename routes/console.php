<?php

// Disable Laravel's default migration commands
collect([
    'migrate',
    'migrate:fresh',
    'migrate:install',
    'migrate:refresh',
    'migrate:reset',
    'migrate:status',
    'migrate:rollback',
])->each(function ($command) {
    Artisan::command($command, function () use ($command) {
        $this->comment("⚠️  {$command} command is disabled.");
    })->describe("{$command} command is disabled.");
});
