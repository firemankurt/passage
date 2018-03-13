<?php namespace KurtJensen\Passage\Updates;

use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;
use Schema;

class CreateVariancesTable extends Migration {
	public function up() {
		Schema::create('kurtjensen_passage_variances', function (Blueprint $table) {
			$table->engine = 'InnoDB';
			$table->increments('id')->unsigned();
			$table->integer('user_id')->unsigned();
			$table->integer('key_id')->unsigned();
			$table->boolean('grant')->default(true);
			$table->boolean('check_one')->default(false);
			$table->string('description')->nullable();
			$table->timestamps();
			$table->unique(['user_id', 'key_id']);
		});
	}

	public function down() {
		Schema::dropIfExists('kurtjensen_passage_variances');
	}
}
