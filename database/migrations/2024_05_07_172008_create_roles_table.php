<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        DB::table('roles')->insert([
            ['name' => 'superadmin', 'description' => 'Super Admin Role'],
            ['name' => 'president', 'description' => 'President Role'],
            ['name' => 'vice_president', 'description' => 'Vice President Role'],
            ['name' => 'finance_secretry', 'description' => 'Finance Secretry Role'],
            ['name' => 'secretry', 'description' => 'Secretry Role'],
            ['name' => 'manager', 'description' => 'Manager Role'],
            ['name' => 'librarian', 'description' => 'Librarian Role'],
            ['name' => 'lawyer', 'description' => 'Lawyer Role'],
            ['name' => 'vendor', 'description' => 'Vendor Role'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
}
