<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterUsersTableAddRequiredFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->date('dob')->nullable()->after('father_last_name');
            $table->tinyInteger('is_deceased')->default(false)->after('status')->comment('No = 0, Yes = 1');
            $table->tinyInteger('is_physically_disabled')->default(false)->after('is_deceased')->comment('No = 0, Yes = 1');
            $table->string('other_details')->nullable()->after('chamber_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('dob');
            $table->dropColumn('is_deceased');
            $table->dropColumn('is_physically_disabled');
            $table->dropColumn('other_details');
        });
    }
}
