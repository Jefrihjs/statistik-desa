<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('desa_id')->nullable()->constrained('desas')->nullOnDelete();

            $table->string('role')->nullable();
            $table->string('module')->nullable();
            $table->string('action');
            $table->text('description')->nullable();

            $table->string('route_name')->nullable();
            $table->string('url')->nullable();
            $table->string('method', 10)->nullable();

            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();

            $table->json('properties')->nullable();

            $table->timestamps();

            $table->index(['user_id', 'desa_id']);
            $table->index(['module', 'action']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};