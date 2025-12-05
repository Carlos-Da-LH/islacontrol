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
        Schema::table('sales', function (Blueprint $table) {
            // Método de pago: efectivo, tarjeta_credito, tarjeta_debito, transferencia, otros
            $table->string('payment_method', 50)->default('efectivo')->after('notes');

            // Monto recibido (útil para efectivo, calcular cambio)
            $table->decimal('amount_received', 10, 2)->nullable()->after('payment_method');

            // Cambio devuelto
            $table->decimal('change_returned', 10, 2)->nullable()->after('amount_received');

            // Referencia de pago (número de transacción, cheque, etc.)
            $table->string('payment_reference', 100)->nullable()->after('change_returned');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'amount_received', 'change_returned', 'payment_reference']);
        });
    }
};
