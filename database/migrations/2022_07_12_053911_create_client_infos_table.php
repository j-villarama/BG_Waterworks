<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_infos', function (Blueprint $table) {
            $table->id();
            $table->string('account_number');
            $table->enum('status', ['Pending Application', 'Active', 'Inactive', 'Delinquent'])->nullable();
            $table->string('contact_number');
            $table->string('client_firstname');
            $table->string('client_lastname');
            $table->string('client_middlename');
            $table->date('client_birthday');
            $table->enum('client_gender', ['Male', 'Female'])->default('Male');
            $table->string('client_adress');
            $table->string('client_profile_photo')->nullable();
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
        Schema::dropIfExists('client_infos');
    }
}
