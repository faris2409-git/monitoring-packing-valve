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
        Schema::table('packaging_tasks', function (Blueprint $table) {
            $table->date('target_delivery_date')->nullable()->after('quantity_packed');
            $table->text('instruction')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('packaging_tasks', function (Blueprint $table) {
            $table->dropColumn('target_delivery_date');
        });
    }
};
