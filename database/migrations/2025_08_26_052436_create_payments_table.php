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
  public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sale_id')->nullable();
            $table->string('stripe_payment_id')->unique(); // Cambiado a stripe_payment_id
            $table->string('status');
            $table->decimal('amount', 8, 2);
            $table->string('payment_method_type');
            $table->timestamps();

            $table->foreign('sale_id')->references('id')->on('sales')->onDelete('set null');
        });
    }

    

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
};
