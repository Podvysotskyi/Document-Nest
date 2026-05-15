<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'sqlite';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::connection('sqlite')->hasTable('roadmap_items')) {
            return;
        }

        Schema::connection('sqlite')->create('roadmap_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('roadmap_phase_id')->constrained('roadmap_phases')->cascadeOnDelete();
            $table->string('title');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_visible')->default(true);
            $table->timestamps();

            $table->index(['roadmap_phase_id', 'is_visible', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('sqlite')->dropIfExists('roadmap_items');
    }
};
