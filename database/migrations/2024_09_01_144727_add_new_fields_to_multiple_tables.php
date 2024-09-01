<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewFieldsToMultipleTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vakalatnamas', function (Blueprint $table) {
            $table->tinyInteger('bulk_issue')->default(false)->after('unique_id')->comment('No = 0, Yes = 1');
            $table->string('last_unique_id')->nullable()->after('bulk_issue');
            $table->integer('number_of_issue_vakalatnamas')->nullable()->after('last_unique_id');
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->string('father_name')->nullable()->after('name');
            $table->string('aadhaar_no')->nullable()->after('gender');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vakalatnamas', function (Blueprint $table) {
            $table->dropColumn([
                'bulk_issue',
                'last_unique_id',
                'number_of_issue_vakalatnamas'
            ]);
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn([
                'father_name',
                'aadhaar_no'
            ]);
        });
    }
}
