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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug');
            $table->string('slug_alternative')->nullable();
            $table->string('description')->nullable();
            $table->json('text');
            $table->integer('views')->default(0);
            $table->boolean('recording')->default(false);
            $table->boolean('enabled')->default(false);
            $table->unsignedBigInteger('owner_id');
            $table->unsignedBigInteger('moder_id')->nullable();
            $table->unsignedBigInteger('poster_id')->nullable();
            $table->unsignedBigInteger('background_id')->nullable();
            $table->timestamp('event_start_at');
            $table->timestamp('event_end_at')->nullable();
            $table->timestamp('record_start_at')->nullable();
            $table->timestamp('record_end_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('owner_id')->references('id')->on('users');
            $table->foreign('moder_id')->references('id')->on('users');
            $table->foreign('poster_id')->references('id')->on('media');
            $table->foreign('background_id')->references('id')->on('media');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
