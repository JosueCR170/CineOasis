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
        Schema::create('detalles_combo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('idTicket')
            ->constrained('tickets')
            ->nullable()
            ->cascadeOnUpdate()
            ->cascadeOnDelete();
            $table->foreignId('idComida')
            ->constrained('comida')
            ->nullable()
            ->cascadeOnUpdate()
            ->cascadeOnDelete();
            $table->integer('cantidad');
            $table->float('subtotal');
            $table->float('descuento')->nullable();
            $table->float('impuesto')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalles_combo');
    }
};
