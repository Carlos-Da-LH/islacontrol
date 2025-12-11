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
        Schema::create('business_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('business_name')->default('MI NEGOCIO');
            $table->string('business_address')->nullable();
            $table->string('business_phone')->nullable();
            $table->string('business_rfc')->nullable();
            $table->string('logo_path')->nullable();
            $table->text('footer_message')->nullable();
            $table->text('extra_message')->nullable();
            $table->boolean('show_logo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('business_settings');
    }
};
