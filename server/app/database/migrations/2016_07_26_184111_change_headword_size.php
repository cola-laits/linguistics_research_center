<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeHeadwordSize extends Migration {

        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
                DB::update('alter table eieol_head_word drop foreign key eieol_head_word_language_id_foreign');
                Schema::table('eieol_head_word', function(Blueprint $table) {
                        $table->dropUnique('eieol_head_word_language_id_word_definition_unique');
                });
                DB::update('ALTER TABLE eieol_head_word MODIFY definition VARCHAR(500)');
                DB::update('ALTER TABLE eieol_head_word ADD CONSTRAINT `eieol_head_word_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `eieol_language` (`id`)');
        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down()
        {
                DB::update('ALTER TABLE eieol_head_word MODIFY definition VARCHAR(255)');
        }

}
