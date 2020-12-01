<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UserPermissionChangeToPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::table('user_permission')->where('permission','ADMIN')->delete();
        Schema::table('user_permission', function (Blueprint $table) {
            $table->dropUnique(['user_id','permission']);
            $table->integer('eieol_series_id')->unsigned();
        });

        $perms = \DB::table('user_permission')->get();
        foreach ($perms as $perm) {
            \DB::table('user_permission')
                ->where('id', $perm->id)
                ->update(['eieol_series_id' => $perm->permission]);
        }
        Schema::table('user_permission', function (Blueprint $table) {
            $table->dropColumn('permission');
            $table->unique(['user_id','eieol_series_id']);
            $table->foreign('eieol_series_id')->references('id')->on('eieol_series');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_permission', function (Blueprint $table) {
            $table->dropColumn('eieol_series_id');
            $table->string('permission');
        });
    }
}
