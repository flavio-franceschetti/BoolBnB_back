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
        Schema::create('apartment_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('apartment_id')->nullable();
            $table->string('img_path');
            $table->string('img_name');
            $table->timestamps();

            $table->foreign('apartment_id')->references('id')->on('apartments')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('apartment_images', function (Blueprint $table) {
            $table->dropForeign(['apartment_id']);
            $table->dropIfExists('apartment_images');
        });
    }
};
