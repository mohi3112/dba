<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('father_first_name')->nullable();
            $table->string('father_last_name')->nullable();
            $table->integer('gender')->comment('Male = 1, Female = 2, Other = 3');
            $table->date('dob')->nullable();
            $table->string('mobile')->nullable();
            $table->string('residence_address')->nullable();
            $table->string('business_name')->nullable();
            $table->string('employees')->nullable();
            $table->integer('location_id')->nullable();
            $table->tinyInteger('status')->default(true)->comment('InActive = 0, Active = 1');
            $table->integer('deleted_by')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('vendors');
    }
}
