<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersUpdateRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_update_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id');
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->nullable();
            $table->string('father_first_name')->nullable();
            $table->string('father_last_name')->nullable();
            $table->date('dob')->nullable();
            $table->integer('gender')->nullable();
            $table->string('mobile1')->nullable();
            $table->string('mobile2')->nullable();
            $table->longText('picture')->nullable();
            $table->string('aadhaar_no')->nullable();
            $table->string('designation')->nullable();
            $table->string('degrees')->nullable();
            $table->string('address')->nullable();
            $table->integer('status')->nullable();
            $table->tinyInteger('is_deceased')->nullable();
            $table->tinyInteger('is_physically_disabled')->nullable();
            $table->string('chamber_number')->nullable();
            $table->string('other_details')->nullable();
            $table->integer('change_type')->nullable()->comment('Edited = 1, Deleted = 2');
            $table->bigInteger('changes_requested_by');
            $table->tinyInteger('approved_by_secretry')->nullable();
            $table->tinyInteger('approved_by_president')->nullable();
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
        Schema::dropIfExists('user_update_requests');
    }
}
