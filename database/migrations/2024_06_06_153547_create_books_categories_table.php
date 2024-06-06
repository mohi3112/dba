<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBooksCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books_categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('category_name');
            $table->string('published_volumns')->nullable();
            $table->string('published_total_volumns')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();

        });
        
        Schema::table('books', function (Blueprint $table) {
            $table->unsignedBigInteger('book_category_id')->after('id');
            $table->string('book_volume')->nullable()->after('book_licence');
            $table->date('publish_date')->nullable()->after('book_licence_valid_upto');
            $table->float('price')->nullable()->after('publish_date');
            $table->foreign('book_category_id')->references('id')->on('books_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('books_categories');
    }
}
