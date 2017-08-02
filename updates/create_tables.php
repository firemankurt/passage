<?php namespace KurtJensen\Passage\Updates;

use October\Rain\Database\Updates\Migration;
use Schema;

class CreateTables extends Migration {

	public function up() {
		Schema::create('kurtjensen_passage_keys', function ($table) {
			$table->engine = 'InnoDB';
			$table->increments('id')->unsigned();
			$table->string('name');
			$table->string('description')->nullable();
			$table->timestamps();
		});

		Schema::create('kurtjensen_passage_groups_keys', function ($table) {
			$table->engine = 'InnoDB';
			$table->integer('user_group_id')->unsigned();
			$table->integer('key_id')->unsigned();
			$table->timestamps();
			$table->primary(['user_group_id', 'key_id'], 'user_group_id');
		});
	}

	public function down() {
		Schema::dropIfExists('kurtjensen_passage_keys');
		Schema::dropIfExists('kurtjensen_passage_groups_keys');
	}

}
