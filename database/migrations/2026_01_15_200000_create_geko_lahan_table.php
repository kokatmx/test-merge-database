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
        Schema::create('geko_lahan', function (Blueprint $table) {
            $table->id();
            $table->string('lahan_no', 100)->unique()->nullable();
            $table->string('document_no', 100)->nullable();
            $table->string('internal_code', 50)->nullable();
            $table->decimal('land_area', 10, 2)->nullable();
            $table->decimal('planting_area', 10, 2)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->text('coordinate')->nullable();
            $table->text('polygon')->nullable();
            $table->string('village', 100)->nullable();
            $table->string('kecamatan', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('province', 100)->nullable();
            $table->integer('elevation')->nullable();
            $table->string('farmer_no', 100)->nullable();
            $table->string('mu_no', 50)->nullable();
            $table->string('target_area', 50)->nullable();
            $table->string('tutupan_lahan', 100)->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();

            // Index untuk performa query
            $table->index('farmer_no');
            $table->index('mu_no');
            $table->index('village');
            $table->index('active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('geko_lahan');
    }
};
