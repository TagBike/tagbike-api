<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->integer('id_user');
            $table->string('name');
            $table->string('cpf', 11);
            $table->string('email')->unique();
            $table->string('password');
            $table->string('cep', 10);
            $table->string('uf', 2);
            $table->string('city', 100);
            $table->string('neighborhood', 100);
            $table->string('address', 100); 
            $table->string('number', 20); 
            $table->string('complement', 100)->nullable(); 
            $table->string('phone', 15);
            $table->string('cellphone', 15)->nullable();
            $table->date('birthday')->nullable();
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
        Schema::dropIfExists('clients');
    }
}
