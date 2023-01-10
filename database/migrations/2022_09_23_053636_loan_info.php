<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LoanInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_info', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->decimal('amount_approved',8,2);
            $table->integer('contract_no_months');
            $table->decimal('interest_rate',8,2);
            $table->enum('current_status', ['Applied','Denied','Approved', 'Released', 'Delinquent', 'Completed'])->default('Applied');
            $table->enum('payment_term',['Weekly','Semi-Monthly','Monthly']);
            
            $table->integer('payment_term_result');
            $table->timestamps();
            $table->foreign('customer_id')
              ->references('id')->on('client_infos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loan_info');
    }
}
