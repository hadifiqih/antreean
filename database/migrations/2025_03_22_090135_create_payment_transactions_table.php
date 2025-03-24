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
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('antrian_id')->constrained('antrians')->onDelete('cascade'); // Mengacu pada pesanan
            $table->decimal('total_amount', 10, 0); // Total pembayaran yang harus dibayar
            $table->string('payment_status')->default('unpaid'); // unpaid, partially_paid, paid
            $table->timestamps();
        });

        Schema::create('installments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payment_transaction_id')->constrained('payment_transactions')->onDelete('cascade');
            $table->decimal('amount', 10, 0); // Jumlah per cicilan
            $table->string('payment_method'); // Metode pembayaran (transfer, cash, etc.)
            $table->string('status')->default('unpaid'); // unpaid, paid
            $table->text('proof_file')->nullable(); // Bukti pembayaran cicilan
            $table->unsignedBigInteger('validated_by')->nullable(); // Tanggal pembayaran cicilan
            $table->timestamps();

            $table->foreign('payment_transaction_id')->references('id')->on('payment_transactions')->onDelete('cascade');
            $table->foreign('validated_by')->references('id')->on('users')->onDelete('set null'); // Mengacu pada pengguna yang memvalidasi
        });

        Schema::create('additional_costs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payment_transaction_id')->constrained('payment_transactions')->onDelete('cascade');
            $table->string('type'); // shipping, installation, tax, etc.
            $table->decimal('amount', 10, 0);  // Jumlah biaya tambahan
            $table->timestamps();

            $table->foreign('payment_transaction_id')->references('id')->on('payment_transactions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
        Schema::dropIfExists('installments');
        Schema::dropIfExists('additional_costs');
    }
};
