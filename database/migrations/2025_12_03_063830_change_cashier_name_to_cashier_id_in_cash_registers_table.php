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
        Schema::table('cash_registers', function (Blueprint $table) {
            // Eliminar el campo cashier_name si existe
            if (Schema::hasColumn('cash_registers', 'cashier_name')) {
                $table->dropColumn('cashier_name');
            }

            // Agregar cashier_id
            $table->unsignedBigInteger('cashier_id')->nullable()->after('user_id');
            $table->foreign('cashier_id')->references('id')->on('cashiers')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cash_registers', function (Blueprint $table) {
            // Eliminar foreign key y columna
            $table->dropForeign(['cashier_id']);
            $table->dropColumn('cashier_id');

            // Restaurar cashier_name
            $table->string('cashier_name')->nullable()->after('user_id');
        });
    }
};
