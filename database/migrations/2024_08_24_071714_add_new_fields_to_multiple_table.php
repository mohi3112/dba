<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewFieldsToMultipleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Add esi_number, bank_account_number, bank_ifsc_code, account_holder_name, branch_name, policies field to employees table
        Schema::table('employees', function (Blueprint $table) {
            $table->string('esi_number')->nullable()->after('salary');
            $table->date('esi_start_date')->nullable()->after('esi_number');
            $table->date('esi_end_date')->nullable()->after('esi_start_date');
            $table->string('esi_contribution')->nullable()->after('esi_end_date');
            $table->bigInteger('bank_account_number')->nullable()->after('esi_number');
            $table->string('bank_ifsc_code')->nullable()->after('bank_account_number');
            $table->string('account_holder_name')->nullable()->after('bank_ifsc_code');
            $table->string('branch_name')->nullable()->after('account_holder_name');
            $table->json('policies')->nullable()->after('branch_name');
        });

        // Add security_deposit field to vendors table
        Schema::table('vendors', function (Blueprint $table) {
            $table->decimal('security_deposit', 10, 2)->nullable()->after('location_id');
        });

        // Add year_of_publishing field to books_categories table
        Schema::table('books_categories', function (Blueprint $table) {
            $table->year('year_of_publishing')->nullable()->after('published_volumes');
        });

        // Add description, issued_by, issued_to, field to vouchers table
        Schema::table('vouchers', function (Blueprint $table) {
            $table->string('description')->nullable()->after('date');
            $table->bigInteger('issued_by')->nullable()->after('description');
            $table->bigInteger('issued_to')->nullable()->after('issued_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn([
                'esi_number',
                'bank_account_number',
                'bank_ifsc_code',
                'account_holder_name',
                'branch_name',
                'policies',
            ]);
        });

        // Remove security_deposit field from vendors table
        Schema::table('vendors', function (Blueprint $table) {
            $table->dropColumn('security_deposit');
        });

        // Remove year_of_publishing field from books_categories table
        Schema::table('books_categories', function (Blueprint $table) {
            $table->dropColumn('year_of_publishing');
        });

        Schema::table('vouchers', function (Blueprint $table) {
            $table->dropColumn([
                'description',
                'issued_by',
                'issued_to'
            ]);
        });
    }
}
