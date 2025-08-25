<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->string('oauth_provider')->nullable()->after('password');
            $table->string('oauth_provider_id')->nullable()->after('oauth_provider');
            $table->string('email')->nullable()->after('oauth_provider_id');
            $table->string('avatar')->nullable()->after('email');
            $table->timestamp('email_verified_at')->nullable()->after('avatar');
            
            // Add index for OAuth lookups
            $table->index(['oauth_provider', 'oauth_provider_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->dropIndex(['oauth_provider', 'oauth_provider_id']);
            $table->dropColumn([
                'oauth_provider',
                'oauth_provider_id',
                'email',
                'avatar',
                'email_verified_at'
            ]);
        });
    }
};
