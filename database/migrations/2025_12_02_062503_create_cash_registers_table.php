<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash_registers', function (Blueprint $table) {
            $table->id();

            // Usuario que maneja la caja
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Montos de la caja
            $table->decimal('opening_balance', 10, 2)->default(0); // Fondo inicial
            $table->decimal('closing_balance', 10, 2)->nullable(); // Efectivo final contado
            $table->decimal('expected_balance', 10, 2)->nullable(); // Efectivo esperado según sistema
            $table->decimal('difference', 10, 2)->nullable(); // Diferencia (faltante o sobrante)

            // Totales de ventas por método de pago
            $table->decimal('total_cash_sales', 10, 2)->default(0); // Ventas en efectivo
            $table->decimal('total_card_sales', 10, 2)->default(0); // Ventas con tarjeta
            $table->decimal('total_transfer_sales', 10, 2)->default(0); // Ventas con transferencia
            $table->decimal('total_sales', 10, 2)->default(0); // Total general

            // Fechas y estado
            $table->datetime('opened_at');
            $table->datetime('closed_at')->nullable();
            $table->enum('status', ['open', 'closed'])->default('open');

            // Notas adicionales
            $table->text('opening_notes')->nullable();
            $table->text('closing_notes')->nullable();

            $table->timestamps();

            // Índices para mejorar rendimiento
            $table->index(['user_id', 'status']);
            $table->index('opened_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cash_registers');
    }
};
