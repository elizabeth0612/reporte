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
        Schema::create('workers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->index()->nullable();
            $table->string('paternal_surname', 100)->nullable();
            $table->string('maternal_surname', 100)->nullable();
            $table->unsignedBigInteger('user_register')->nullable();
            $table->foreign('user_register')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('user_update')->nullable();
            $table->foreign('user_update')->references('id')->on('users')->onDelete('cascade');
            $table->boolean('status')->default(1);
            $table->timestamp('fecha_registro')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('fecha_actualizacion')->default(DB::raw('CURRENT_TIMESTAMP'))->onUpdate(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workers');
    }
};
