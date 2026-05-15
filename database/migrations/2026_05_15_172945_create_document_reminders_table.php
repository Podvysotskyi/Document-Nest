<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_reminders', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('document_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
            $table->date('remind_on');
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            $table->unique(['document_id', 'remind_on']);
            $table->index('remind_on');
            $table->index('sent_at');
            $table->index(['user_id', 'remind_on']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_reminders');
    }
};
