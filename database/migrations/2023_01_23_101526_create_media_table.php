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
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->string('fullname');
            $table->string('path');
            $table->string('description')->nullable();
            $table->string('type')->nullable();
            $table->string('extension')->nullable();
            $table->bigInteger('size')->nullable();
            $table->unsignedBigInteger('owner_id');
            $table->unsignedBigInteger('moder_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('owner_id')->references('id')->on('users');
            $table->foreign('moder_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
