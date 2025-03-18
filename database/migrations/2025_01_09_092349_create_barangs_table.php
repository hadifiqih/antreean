<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barangs', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_order');
            $table->foreignId('data_kerja_id')->default(0);
            $table->foreignId('customer_id')->constrained('customers');
            $table->foreignId('kategori_id')->default(1);
            $table->foreignId('job_id')->constrained('jobs');
            $table->foreignId('sales_id')->constrained('sales');
            $table->decimal('price', 10, 0);
            $table->integer('qty');
            $table->text('note')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};
