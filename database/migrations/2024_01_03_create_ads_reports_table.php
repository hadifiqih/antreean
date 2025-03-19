<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ads_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('daily_report_id')->constrained('daily_reports')->onDelete('cascade');
            $table->string('ads_id');
            $table->integer('lead_amount')->default(0);
            $table->decimal('total_omset', 12, 2)->default(0);
            $table->text('analisa')->nullable();
            $table->text('kendala')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ads_reports');
    }
};
