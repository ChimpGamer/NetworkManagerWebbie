<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Check account groups
$groups = App\Models\Group::all(['name', 'administrator', 'manage_groups_and_accounts']);
echo "Account Groups:\n";
foreach ($groups as $group) {
    echo "Name: {$group->name}, Administrator: " . ($group->administrator ? 'Yes' : 'No') . ", Manage Groups: " . ($group->manage_groups_and_accounts ? 'Yes' : 'No') . "\n";
}

// Check current users and their groups
$users = App\Models\User::with('group')->get(['username', 'usergroup', 'oauth_provider']);
echo "\nUsers:\n";
foreach ($users as $user) {
    echo "Username: {$user->username}, Group: {$user->usergroup}, OAuth: " . ($user->oauth_provider ?? 'No') . "\n";
}