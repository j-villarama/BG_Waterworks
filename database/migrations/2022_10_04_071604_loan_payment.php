<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LoanPayment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_payment', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('loan_info_id');
            $table->integer('payment_no')->nullable();
            $table->decimal('amount',8,2);
            $table->date('payment_date')->nullable();
            $table->date('due_date')->nullable();
            $table->decimal('penalty',8,2)->nullable();
            $table->boolean('is_overdue')->default(0);
            $table->enum('paid_status', ['Unpaid', 'Paid', 'Overdue']);
            $table->timestamps();
            $table->foreign('loan_info_id')
              ->references('id')->on('loan_info')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loan_payment');
    }
}
