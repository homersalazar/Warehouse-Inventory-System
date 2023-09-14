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
        Schema::create('pendings', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('prod_sku'); // change to prod_sku
            $table->date('tran_date');
            $table->integer('tran_quantity');
            $table->string('tran_serial')->nullable();
            $table->string('tran_remarks')->nullable();
            $table->string('tran_action')->nullable();
            $table->string('tran_drno')->nullable();
            $table->string('tran_mpr')->nullable();
            $table->string('tran_from')->nullable();
            $table->string('location_id')->nullable();
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
        Schema::dropIfExists('pendings');
    }
};
