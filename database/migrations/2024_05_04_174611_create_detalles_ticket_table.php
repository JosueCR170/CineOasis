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
        Schema::create('detalles_ticket', function (Blueprint $table) {
            $table->id();
            $table->foreignId('idTicket')
            ->constrained('tickets')
            ->nullable()
            ->cascadeOnUpdate()
            ->cascadeOnDelete();

            $table->foreignId('idAsiento')
            ->constrained('asientos')
            ->nullable()
            ->cascadeOnUpdate()
            ->cascadeOnDelete();
            $table->float('subtotal');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalles_ticket');
    }
};
