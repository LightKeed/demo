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
        Schema::create('slides', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description')->nullable();
            $table->string('text_button')->nullable();
            $table->string('link_button')->nullable();
            $table->tinyInteger('sort_order')->default(0);
            $table->boolean('enabled')->default(false);
            $table->unsignedBigInteger('slider_id')->nullable();
            $table->unsignedBigInteger('media_id')->nullable();
            $table->timestamps();
            $table->foreign('slider_id')->references('id')->on('sliders');
            $table->foreign('media_id')->references('id')->on('media');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('slides');
    }
};
