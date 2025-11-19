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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            // Agrega la columna 'sale_date' aquí
            $table->date('sale_date');
            $table->decimal('amount', 8, 2);
            $table->boolean('is_credit')->default(false);
            $table->decimal('paid_amount', 8, 2)->nullable();
            $table->boolean('is_paid')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();

            // Definición de la clave foránea
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales');
    }
};