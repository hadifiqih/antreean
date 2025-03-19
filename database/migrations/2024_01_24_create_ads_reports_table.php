<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdsReportsTable extends Migration
{
    public function up()
    {
        Schema::create('ads_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('daily_report_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('ads_id');
            $table->integer('lead_amount')->default(0);
            $table->decimal('total_omset', 15, 2)->default(0);
            $table->text('analisa')->nullable();
            $table->text('kendala')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ads_reports');
    }
}
