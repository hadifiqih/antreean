<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('antrian_iklans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('platform_id')->constrained('platforms')->onDelete('cascade');
            $table->foreignId('sales_id')->constrained('sales')->onDelete('cascade');
            $table->foreignId('job_id')->constrained('jobs')->onDelete('cascade');
            $table->foreignId('barang_id')->constrained('barangs')->onDelete('cascade');
            $table->boolean('is_iklan')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('antrian_iklans');
    }
};
