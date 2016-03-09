<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaperFinishingPricesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if(!Schema::hasTable('paper_finishing_prices'))
		{
			Schema::create('paper_finishing_prices', function($table){
				$table->increments('id');
				$table->string('paper_size_code');
				$table->string('finishing_code');
				$table->string('paper_product');
				$table->string('option_1_code');
				$table->string('option_2_code');
				$table->string('option_3_code');
				$table->string('option_4_code');
				$table->string('option_5_code');
				$table->integer('minimun_price');
				$table->string('minimum_folded_size');
				$table->string('price_per_page')->default('x');
				$table->string('turn_around_time')->default('x');
				$table->timestamps();
				$table->engine = 'InnoDB';
			});
		}

		if(!Schema::hasTable('paper_color_prices'))
		{
			Schema::create('paper_color_prices', function($table){
				$table->increments('id');
				$table->string('paper_size_code');
				$table->string('color_code');
				$table->string('paper_weight');
				$table->integer('day');
				$table->string('quantity')->default('x');
				$table->string('price')->default('x');
				$table->timestamps();
				$table->engine = 'InnoDB';
			});
		}
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('paper_finishing_prices');
		Schema::drop('paper_color_prices');
	}

}
