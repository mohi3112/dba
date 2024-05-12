<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop columns
            $table->dropColumn(['name', 'remember_token']);
            // Add new columns
            $table->string('first_name')->after('id');
            $table->string('middle_name')->after('first_name')->nullable();
            $table->string('last_name')->after('middle_name')->nullable();
            $table->string('father_first_name')->after('email')->nullable();
            $table->string('father_last_name')->after('father_first_name')->nullable();
            $table->integer('gender')->comment('Male = 1, Female = 2, Other = 3')->after('father_last_name');
            $table->integer('mobile1')->after('gender');
            $table->integer('mobile2')->nullable()->after('mobile1');
            $table->string('designation')->nullable()->after('mobile2');
            $table->string('degrees')->nullable()->after('designation');
            $table->string('address')->after('degrees');
            $table->string('chamber_number')->nullable()->after('address');
            $table->integer('floor_number')->nullable()->after('chamber_number');
            $table->string('building')->nullable()->after('floor_number');
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
            // Add back dropped columns
            $table->string('name');
            $table->string('remember_token', 100)->nullable()->default(null);
            // Drop the column
            // Drop new columns
            $table->dropColumn([
                'first_name',
                'middle_name',
                'last_name',
                'father_first_name',
                'father_last_name',
                'gender',
                'mobile1',
                'mobile2',
                'designation',
                'degrees',
                'address',
                'chamber_number',
                'floor_number',
                'building'
            ]);
        });
    }
}
