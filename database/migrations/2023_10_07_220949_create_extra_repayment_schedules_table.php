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
        Schema::create('extra_repayment_schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('loan_id');
            $table->integer('month_number');
            $table->decimal('starting_balance', 10, 2)->default(0.00);
            $table->decimal('monthly_payment', 10, 2)->default(0.00);
            $table->decimal('principal_component', 10, 2)->default(0.00);
            $table->decimal('interest_component', 10, 2)->default(0.00);
            $table->decimal('extra_repayment_made', 10, 2)->nullable();
            $table->decimal('ending_balance', 10, 2)->default(0.00);
            $table->integer('remaining_loan_term');
            $table->timestamps();

            // foreign key constraint
            $table->foreign('loan_id')
                ->references('id')
                ->on('loans')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('extra_repayment_schedules');
    }
};
