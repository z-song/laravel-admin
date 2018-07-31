<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTableAdminMenu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $connection = config('admin.database.connection') ?: config('database.default');
        Schema::connection($connection)->table(config('admin.database.menu_table'), function (Blueprint $table) {
            $table->unsignedTinyInteger('menu_type')->default(0);
            $table->integer('menu_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $connection = config('admin.database.connection') ?: config('database.default');
        /*
        Schema::connection($connection)->table(config('admin.database.menu_table'), function (Blueprint $table) {
            $table->unsignedTinyInteger('menu_type',3)->default(0);
            $table->integer('menu_id')->default(0);
        });
        */
    }
}
