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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('product_id');
            $table->date('tran_date');
            $table->string('tran_option')->nullable();
            $table->integer('tran_quantity');
            $table->string('tran_unit')->nullable();
            $table->string('tran_serial')->nullable();
            $table->string('tran_comment')->nullable();
            $table->string('tran_action')->nullable();
            $table->smallInteger('user_id');
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
        Schema::dropIfExists('transactions');
    }
};
