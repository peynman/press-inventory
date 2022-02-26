<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_stocks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('author_id', false, true);
            $table->bigInteger('product_id', false, true);
            $table->bigInteger('branch_id', false, true)->nullable();
            $table->bigInteger('group_id', false, true)->nullable();
            $table->integer('qoh')->default(0);
            $table->integer('status')->default(0);
            $table->integer('priority')->default(0);
            $table->json('data')->nullable();
            $table->integer('flags', false, true)->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index(
                [
                    'deleted_at',
                    'created_at',
                    'updated_at',
                    'author_id',
                    'product_id',
                    'branch_id',
                    'group_id',
                ],
                'products_stocks_full_index'
            );

            $table->foreign('author_id')->references('id')->on('users');
            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('group_id')->references('id')->on('groups');
            $table->foreign('branch_id')->references('id')->on('inventory_branches');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_stocks');
    }
}
