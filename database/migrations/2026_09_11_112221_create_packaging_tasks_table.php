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
        Schema::create('packaging_tasks', function (Blueprint $table) {
            $table->id();
            $table->string('task_code')->unique();
            $table->foreignId('engine_valve_id')->constrained()->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('assigned_to')->constrained('users')->onDelete('cascade');
            $table->integer('quantity_target');
            $table->integer('quantity_packed')->default(0);
            $table->text('instruction')->nullable();
            $table->enum('status', ['assigned', 'accepted', 'in_progress', 'completed'])->default('assigned');
            $table->text('notes')->nullable();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packaging_tasks');
    }
};
