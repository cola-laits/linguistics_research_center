<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UserMergeFirstLastName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user', function(Blueprint $table) {
            $table->string('name');
        });

        $users = \DB::table('user')->get();
        foreach ($users as $user) {
            $name = trim($user->first_name . ' ' . $user->last_name);
            \DB::table('user')
                ->where('id', $user->id)
                ->update(['name' => $name]);
        }

        Schema::table('user', function(Blueprint $table) {
            $table->dropColumn('last_name');
            $table->dropColumn('first_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user', function(Blueprint $table) {
            $table->renameColumn('name', 'first_name');
            $table->string('last_name');
        });
    }
}
