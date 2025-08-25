<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Update administrator group to have manage_groups_and_accounts permission
$adminGroup = App\Models\Group::where('name', 'administrator')->first();

if ($adminGroup) {
    $adminGroup->update([
        'manage_groups_and_accounts' => true,
        'view_accounts' => true,
        'edit_accounts' => true
    ]);
    echo "Administrator group permissions updated successfully!\n";
    echo "manage_groups_and_accounts: " . ($adminGroup->fresh()->manage_groups_and_accounts ? 'Yes' : 'No') . "\n";
} else {
    echo "Administrator group not found!\n";
}

// Also check if there's a 'default' group and create it if it doesn't exist
$defaultGroup = App\Models\Group::where('name', 'default')->first();

if (!$defaultGroup) {
    App\Models\Group::create([
        'name' => 'default',
        'administrator' => false,
        'manage_groups_and_accounts' => false,
        'view_accounts' => false,
        'edit_accounts' => false,
        'view_analytics' => false,
        'view_network' => false,
        'view_players' => true,
        'view_chat' => false
    ]);
    echo "Default group created successfully!\n";
} else {
    echo "Default group already exists.\n";
}