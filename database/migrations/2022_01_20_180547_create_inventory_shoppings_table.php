<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryShoppingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_shoppings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inventory_id');
            $table->foreign('inventory_id')->references('id')->on('inventories')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('shopping_id');
            $table->foreign('shopping_id')->references('id')->on('shoppings')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('total',8,2);
            $table->decimal('discount',8,2)->default(0.00);
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
        Schema::dropIfExists('inventory_shoppings');
    }
}
