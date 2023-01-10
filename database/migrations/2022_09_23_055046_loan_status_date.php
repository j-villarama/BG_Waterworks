<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LoanStatusDate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_status_date', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('loan_info_id');
            // $table->unsignedBigInteger('loan_status_id');
            $table->enum('status', ['Applied','Denied','Approved', 'Released', 'Delinquent', 'Completed'])->default('Applied');
            $table->decimal('actual_amount_on_status',8,2);
            $table->date('status_date');
            $table->text('remarks')->nullable();
            $table->timestamps();
            $table->foreign('loan_info_id')
              ->references('id')->on('loan_info')->onDelete('cascade');
            //   $table->foreign('loan_status_id')
            //   ->references('id')->on('client_infos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loan_status_date');
    }
}
