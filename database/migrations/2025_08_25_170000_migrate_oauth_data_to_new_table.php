<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Migrate existing OAuth data from accounts table to user_oauth_providers table
        $oauthAccounts = DB::connection('manager')
            ->table('accounts')
            ->whereNotNull('oauth_provider')
            ->whereNotNull('oauth_provider_id')
            ->get();

        foreach ($oauthAccounts as $account) {
            // Check if this OAuth provider is already in the new table
            $existingProvider = DB::connection('manager')
                ->table('user_oauth_providers')
                ->where('user_id', $account->id)
                ->where('provider', $account->oauth_provider)
                ->where('provider_id', $account->oauth_provider_id)
                ->first();

            if (!$existingProvider) {
                // Insert into new table
                DB::connection('manager')
                    ->table('user_oauth_providers')
                    ->insert([
                        'user_id' => $account->id,
                        'provider' => $account->oauth_provider,
                        'provider_id' => $account->oauth_provider_id,
                        'provider_email' => $account->email,
                        'provider_avatar' => $account->avatar,
                        'provider_data' => json_encode([
                            'name' => $account->username,
                            'email' => $account->email,
                            'avatar' => $account->avatar,
                        ]),
                        'linked_at' => $account->created_at ?? now(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove migrated data from user_oauth_providers table
        // This will only remove entries that match existing oauth data in accounts table
        $oauthAccounts = DB::connection('manager')
            ->table('accounts')
            ->whereNotNull('oauth_provider')
            ->whereNotNull('oauth_provider_id')
            ->get();

        foreach ($oauthAccounts as $account) {
            DB::connection('manager')
                ->table('user_oauth_providers')
                ->where('user_id', $account->id)
                ->where('provider', $account->oauth_provider)
                ->where('provider_id', $account->oauth_provider_id)
                ->delete();
        }
    }
};