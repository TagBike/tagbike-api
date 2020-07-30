<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBikesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bikes', function (Blueprint $table) {
            $table->id();
            $table->string('serialNumber')->unique();
            $table->string('biketype', 20);
            $table->string('brand', 20);
            $table->string('model', 20);
            $table->string('color', 50);
            $table->string('photoBike')->nullable();
            $table->string('forwardExchange', 20)->nullable();
            $table->string('rearDerailleur', 20)->nullable();
            $table->string('brakeType', 20)->nullable();
            $table->string('typeSuspension', 20)->nullable();
            $table->string('wheelType', 20)->nullable();
            $table->string('forkType', 20)->nullable();
            $table->string('frametype', 20)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bikes');
    }
}
