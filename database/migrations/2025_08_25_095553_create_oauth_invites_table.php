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
        Schema::create('oauth_invites', function (Blueprint $table) {
            $table->id();
            $table->string('code', 32)->unique(); // Invite code
            $table->string('email')->nullable(); // Optional: restrict to specific email
            $table->unsignedBigInteger('created_by'); // Admin who created the invite
            $table->unsignedBigInteger('used_by')->nullable(); // User who used the invite
            $table->timestamp('expires_at')->nullable(); // Expiration date
            $table->timestamp('used_at')->nullable(); // When it was used
            $table->boolean('single_use')->default(true); // Can only be used once
            $table->integer('max_uses')->default(1); // Maximum number of uses
            $table->integer('used_count')->default(0); // How many times it's been used
            $table->json('metadata')->nullable(); // Additional data (restrictions, etc.)
            $table->timestamps();
            
            $table->foreign('created_by')->references('id')->on('accounts')->onDelete('cascade');
            $table->foreign('used_by')->references('id')->on('accounts')->onDelete('set null');
            $table->index(['code', 'expires_at']);
            $table->index('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('oauth_invites');
    }
};
