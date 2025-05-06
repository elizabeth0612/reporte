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
        Schema::create('work_order_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_order_id')->nullable()->constrained('work_orders')->onDelete('cascade');// parte del trabajo diario
            $table->text('nro_trabajo')->nullable();      // numero de trabajo
            $table->text('descripcion')->nullable();        // Lista de materiales usados
            $table->text('materiales')->nullable();        // Lista de materiales usados
            $table->text('herramientas')->nullable();      // Herramientas requeridas
            $table->text('observaciones')->nullable();     // Observaciones adicionales
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_order_details');
    }
};
