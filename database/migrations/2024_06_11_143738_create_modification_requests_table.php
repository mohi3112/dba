<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModificationRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modification_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('table_name');
            $table->unsignedBigInteger('record_id');
            $table->json('changes')->nullable();
            $table->integer('action')->comment('update = 1, delete = 2')->default(1);
            $table->bigInteger('requested_by')->nullable();
            $table->tinyInteger('approved_by_secretary')->nullable();
            $table->tinyInteger('approved_by_president')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();

            // Indexes
            $table->index(['table_name', 'record_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('modification_requests');
    }
}
