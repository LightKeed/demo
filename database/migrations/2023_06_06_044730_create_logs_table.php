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
        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->string('action');
            $table->string('method')->nullable();
            $table->string('component');
            $table->integer('object_id')->unsigned()->index()->nullable();
            $table->text('note')->nullable();
            $table->string('target');
            $table->integer('target_id')->unsigned()->index()->nullable();
            $table->string('agent')->nullable();
            $table->string('url')->nullable();
            $table->string('ip')->index();
            $table->timestamp('created_at')->index()->useCurrent();
            $table->index(['action', 'component', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logs');
    }
};
