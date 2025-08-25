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
        Schema::connection('manager')->create('user_oauth_providers', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('provider', 50);
            $table->string('provider_id');
            $table->string('provider_email')->nullable();
            $table->string('provider_avatar')->nullable();
            $table->json('provider_data')->nullable();
            $table->timestamp('linked_at');
            $table->timestamps();

            // Indexes
            $table->index(['user_id']);
            $table->index(['provider', 'provider_id']);
            $table->unique(['user_id', 'provider']);

            // Foreign key constraint
            $table->foreign('user_id')->references('id')->on('accounts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('manager')->dropIfExists('user_oauth_providers');
    }
};
