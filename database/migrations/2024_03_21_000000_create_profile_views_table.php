<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfileViewsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('profile_views', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('viewer_user_id');
            $table->string('viewer_full_name');
            $table->unsignedBigInteger('viewed_user_id');
            $table->timestamp('viewed_at');
            $table->boolean('notification_viewed')->default(false);
            $table->timestamps();

            $table->foreign('viewer_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('viewed_user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('profile_views');
    }
}; 