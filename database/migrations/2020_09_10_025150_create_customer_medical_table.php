<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerMedicalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_medicals', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_id');
            $table->string('referral_hospital')->nullable();
            $table->string('observations')->nullable();
            $table->string('emergency_contacts')->nullable();
            $table->string('doctor')->nullable();
            $table->string('bloodtype')->nullable();
            $table->string('allergic_reactions')->nullable();
            $table->string('medicines')->nullable();
            $table->string('additional_notes')->nullable();
            $table->string('insurance')->nullable();
            $table->string('insurance_number')->nullable();
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
        Schema::dropIfExists('customer_medical');
    }
}
