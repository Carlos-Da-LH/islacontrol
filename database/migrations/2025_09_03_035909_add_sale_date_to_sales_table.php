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
            // Agrega la columna `sale_date` de tipo `date`
            // `after('customer_id')` es opcional, pero ayuda a mantener el orden
            $table->date('sale_date')->after('customer_id');
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
            // Elimina la columna `sale_date` si se revierte la migración
            $table->dropColumn('sale_date');
        });
    }
};