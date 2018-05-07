<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class FixUtf8 extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {   
        $charset = "utf8mb4";
        $collate = $charset."_bin";
        $dbName = Config::get('database.connections.'.Config::get('database.default').'.database');
        $query = "ALTER SCHEMA $dbName DEFAULT CHARACTER SET $charset DEFAULT COLLATE $collate;\n"; 
        DB::connection()->getPdo()->exec($query);

        $dbName = Config::get('database.connections.'.Config::get('database.default').'.database');
        $result = DB::select(DB::raw('show tables'));
        
       // $query = "ALTER TABLE lex_etyma DROP INDEX lex_etyma_entry_unique; \n";
      //  echo $query;
      //  DB::connection()->getPdo()->exec($query);
                
        $test = DB::select(DB::raw("select * from INFORMATION_SCHEMA.COLUMNS where DATA_TYPE = 'varchar' AND TABLE_SCHEMA = '$dbName';"));
        //var_dump($test);
        foreach($test as $t)
        {
            $query = "ALTER TABLE $t->TABLE_NAME CHANGE $t->COLUMN_NAME $t->COLUMN_NAME VARCHAR(191) CHARACTER SET $charset COLLATE $collate; \n";
            echo $query;
            DB::connection()->getPdo()->exec($query);
        }
        $test = DB::select(DB::raw("select * from INFORMATION_SCHEMA.COLUMNS where DATA_TYPE = 'text' AND TABLE_SCHEMA = '$dbName';"));
        foreach($test as $t)
        {
            $query = "ALTER TABLE $t->TABLE_NAME CHANGE $t->COLUMN_NAME $t->COLUMN_NAME TEXT CHARACTER SET $charset COLLATE $collate; \n";
            echo $query;
            DB::connection()->getPdo()->exec($query);
        }
        $test = DB::select(DB::raw("select * from INFORMATION_SCHEMA.COLUMNS where DATA_TYPE = 'longtext' AND TABLE_SCHEMA = '$dbName';"));
        foreach($test as $t)
        {
            $query = "ALTER TABLE $t->TABLE_NAME CHANGE $t->COLUMN_NAME $t->COLUMN_NAME LONGTEXT CHARACTER SET $charset COLLATE $collate; \n";
            echo $query;
            DB::connection()->getPdo()->exec($query);
        }
        

        $result = DB::select(DB::raw('show tables'));
        foreach($result as $r)
        {
            foreach($r as $k => $t)
            {
                $query = "ALTER TABLE `$t` CONVERT TO CHARACTER SET $charset COLLATE $collate; \n";
                echo $query;
                DB::connection()->getPdo()->exec($query);
            }
        }
        echo "DB CHARSET set to $charset , $collate";
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $charset = "utf8";
        $collate = $charset."_bin";
        $dbName = Config::get('database.connections.'.Config::get('database.default').'.database');
        $query = "ALTER SCHEMA $dbName DEFAULT CHARACTER SET $charset DEFAULT COLLATE $collate;\n"; 
        DB::connection()->getPdo()->exec($query);
        
        $dbName = Config::get('database.connections.'.Config::get('database.default').'.database');
        $result = DB::select(DB::raw('show tables'));
        $test = DB::select(DB::raw("select * from INFORMATION_SCHEMA.COLUMNS where DATA_TYPE = 'varchar' AND TABLE_SCHEMA = '$dbName';"));
        //var_dump($test);
        foreach($test as $t)
        {
            $query = "ALTER TABLE $t->TABLE_NAME CHANGE $t->COLUMN_NAME $t->COLUMN_NAME VARCHAR(255) CHARACTER SET $charset COLLATE $collate; \n";
            echo $query;
            DB::connection()->getPdo()->exec($query);
        }
        $test = DB::select(DB::raw("select * from INFORMATION_SCHEMA.COLUMNS where DATA_TYPE = 'text' AND TABLE_SCHEMA = '$dbName';"));
        foreach($test as $t)
        {
            $query = "ALTER TABLE $t->TABLE_NAME CHANGE $t->COLUMN_NAME $t->COLUMN_NAME TEXT CHARACTER SET $charset COLLATE $collate; \n";
            echo $query;
            DB::connection()->getPdo()->exec($query);
        }
        $test = DB::select(DB::raw("select * from INFORMATION_SCHEMA.COLUMNS where DATA_TYPE = 'longtext' AND TABLE_SCHEMA = '$dbName';"));
        foreach($test as $t)
        {
            $query = "ALTER TABLE $t->TABLE_NAME CHANGE $t->COLUMN_NAME $t->COLUMN_NAME LONGTEXT CHARACTER SET $charset COLLATE $collate; \n";
            echo $query;
            DB::connection()->getPdo()->exec($query);
        }

        $result = DB::select(DB::raw('show tables'));
        foreach($result as $r)
        {
            foreach($r as $k => $t)
            {
                $query = "ALTER TABLE `$t` CONVERT TO CHARACTER SET $charset COLLATE $collate; \n";
                echo $query;
                DB::connection()->getPdo()->exec($query);
            }
        }
        echo "DB CHARSET set to $charset , $collate";
        
      //  $query = "ALTER TABLE lex_etyma ADD UNIQUE lex_etyma_entry_unique (entry) USING BTREE; \n";
      //  echo $query;
      //  DB::connection()->getPdo()->exec($query);
        
    }

}