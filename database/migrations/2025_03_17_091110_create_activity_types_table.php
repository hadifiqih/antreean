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
        Schema::create('activity_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sales_id');
            $table->string('job_id')->unique();
            $table->decimal('price', 10, 0)->default(0);
            $table->decimal('qty', 10, 0)->default(0);
            $table->decimal('total', 10, 0)->default(0);
            $table->unsignedBigInteger('platform_id');
            $table->json('updates');
            $table->timestamps();

            $table->foreign('sales_id')->references('id')->on('sales');
            $table->foreign('platform_id')->references('id')->on('platforms');
        });

        Schema::create('daily_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sales_id');
            $table->unsignedBigInteger('user_id');
            $table->decimal('omset', 10, 0)->default(0);
            $table->timestamps();

            $table->foreign('sales_id')->references('id')->on('sales');
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::create('daily_activities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('daily_report_id')->constrained('daily_reports');
            $table->unsignedBigInteger('activity_type_id')->constrained('activity_types');
            $table->string('description');
            $table->decimal('amount', 10, 0)->default(0);
            $table->timestamps();
        });

        Schema::create('daily_offers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('daily_report_id')->constrained('daily_reports');
            $table->unsignedBigInteger('offer_id')->constrained('offers');
            $table->boolean('is_prospect')->default(false);
            $table->json('updates')->nullable();
            $table->timestamps();

            $table->foreign('offer_id')->references('id')->on('offers');
            $table->unique(['daily_report_id', 'offer_id']);
            $table->foreign('daily_report_id')->references('id')->on('daily_reports');
        });

        Schema::create('ads_report', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sales_id');
            $table->unsignedBigInteger('platform_id');
            $table->string('job_name');
            $table->decimal('lead_amount', 10, 0)->default(0);
            $table->decimal('total_omset', 10, 0)->default(0);
            $table->text('analisa')->nullable();
            $table->text('kendala')->nullable();
            $table->timestamps();

            $table->foreign('sales_id')->references('id')->on('sales');
            $table->foreign('platform_id')->references('id')->on('platforms');
        });

        Schema::create('ads_problems', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sales_id');
            $table->unsignedBigInteger('ads_report_id');
            $table->text('problem')->nullable();
            $table->timestamps();

            $table->foreign('ads_report_id')->references('id')->on('ads_report');
            $table->foreign('sales_id')->references('id')->on('sales');
        });

        Schema::create('problems', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sales_id');
            $table->text('problem')->nullable();
            $table->timestamps();

            $table->foreign('sales_id')->references('id')->on('sales');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_types');
        Schema::dropIfExists('offers');
        Schema::dropIfExists('daily_reports');
        Schema::dropIfExists('daily_activities');
        Schema::dropIfExists('daily_offers');
        Schema::dropIfExists('ads_report');
        Schema::dropIfExists('ads_problems');
        Schema::dropIfExists('problems');
    }
};
