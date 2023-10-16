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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug');
            $table->string('slug_alternative')->nullable();
            $table->unsignedBigInteger('owner_id');
            $table->unsignedBigInteger('moder_id')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('poster_id')->nullable();
            $table->unsignedBigInteger('background_id')->nullable();
            $table->unsignedBigInteger('slider_id')->nullable();
            $table->string('description')->nullable();
            $table->json('text')->nullable();
            $table->tinyInteger('sort_order')->default(0);
            $table->boolean('enabled')->default(false);
            $table->boolean('enabled_menu')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('owner_id')->references('id')->on('users');
            $table->foreign('moder_id')->references('id')->on('users');
            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('poster_id')->references('id')->on('media');
            $table->foreign('background_id')->references('id')->on('media');
            $table->foreign('slider_id')->references('id')->on('sliders');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
