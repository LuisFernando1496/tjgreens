<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Products extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('stock')->nullable();
            $table->decimal('cost',8,2);
            $table->decimal('price_1',8,2);
            $table->decimal('price_2',8,2)->nullable();
            $table->decimal('price_3',8,2)->nullable();
            $table->string('bar_code');
            $table->date('expiration')->nullable();
            $table->decimal('iva',8,2)->nullable();
            $table->string('product_key')->nullable();
            $table->string('unit_product_key')->nullable();
            $table->string('lot')->nullable();
            $table->string('ieps')->nullable();
            $table->boolean('status')->default(true);
            $table->unsignedBigInteger('branch_office_id')->nullable();
            $table->foreign('branch_office_id')->references('id')->on('branch_offices')->onDelete('cascade');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade');
            $table->unsignedBigInteger('provider_id')->nullable();
            $table->foreign('provider_id')->references('id')->on('providers')->onDelete('cascade');

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
        Schema::dropIfExists('products');
    }
}
