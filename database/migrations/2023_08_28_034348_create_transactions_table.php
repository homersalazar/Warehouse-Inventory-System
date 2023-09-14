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
            $table->smallInteger('prod_sku');
            $table->date('tran_date');
            $table->smallInteger('tran_option')->nullable();
            $table->integer('tran_quantity');
            $table->string('tran_unit')->nullable();
            $table->string('tran_serial')->nullable();
            $table->string('tran_remarks')->nullable();
            $table->smallInteger('tran_action')->nullable();
            $table->string('tran_drno')->nullable();
            $table->string('tran_mpr')->nullable();
            $table->smallInteger('area_id')->nullable();
            $table->smallInteger('equipment_id')->nullable();
            $table->smallInteger('location_id')->nullable();
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
