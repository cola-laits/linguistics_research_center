<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIsAdmin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user', function(Blueprint $table) {
            $table->boolean('is_admin');
        });

        $users = \DB::table('user')->get();
        foreach ($users as $user) {
            $num_admin_perms = \DB::table('user_permission')
                ->where('user_id', $user->id)
                ->where('permission', 'ADMIN')
                ->count();
            if ($num_admin_perms > 0) {
                \DB::table('user')
                    ->where('id', $user->id)
                    ->update(['is_admin' => 1]);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user', function(Blueprint $table) {
            $table->dropColumn('is_admin');
        });
    }
}
