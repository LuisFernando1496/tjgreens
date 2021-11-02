<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Sales extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->integer('payment_type'); //0 = efectivo, 1 = tarjeta
            $table->boolean('status')->default(true);
            $table->decimal('amount_discount',8,2)->nullable();
            $table->integer('discount')->nullable();
            $table->decimal('cart_subtotal',8,2);
            $table->decimal('cart_total',8,2);
            $table->decimal('turned',8,2)->default(0);
            $table->decimal('ingress',8,2)->default(0);
            $table->decimal('total_cost',8,2)->nullable();
            $table->string('status_credit')->nullable();
            $table->unsignedBigInteger('cash_closing_id')->nullable();
            $table->foreign('cash_closing_id')->references('id')->on('cash_closings')->onDelete('cascade');
            $table->integer('folio_branch_office');
            $table->unsignedBigInteger('shopping_cart_id');
            $table->foreign('shopping_cart_id')->references('id')->on('shopping_carts')->onDelete('cascade');
            $table->unsignedBigInteger('branch_office_id');
            $table->foreign('branch_office_id')->references('id')->on('branch_offices')->onDelete('cascade');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('client_id')->nullable();
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
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
        Schema::dropIfExists('sales');
    }
}
