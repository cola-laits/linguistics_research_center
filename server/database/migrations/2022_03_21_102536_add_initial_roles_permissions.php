<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected function add_role_permission($role_name, $permission_names) {
        $role_id = DB::table('roles')->insertGetId([
                                                       'name'=>$role_name,
                                                       'guard_name'=>'web',
                                                   ]);

        foreach ($permission_names as $permission_name) {
            $permission_id = DB::table('permissions')->insertGetId([
                                                                       'name' => $permission_name,
                                                                       'guard_name' => 'web',
                                                                   ]);

            DB::table('role_has_permissions')->insert([
                                                          'role_id' => $role_id,
                                                          'permission_id' => $permission_id,
                                                      ]);
        }
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->add_role_permission('Site Manager', ['manage_users','manage_settings','manage_pages','manage_menu','manage_books']);
        $this->add_role_permission('EIEOL Manager', ['manage_eieol']);
        $this->add_role_permission('Lexicon Manager', ['manage_lexicon']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
