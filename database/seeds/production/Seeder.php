<?php

namespace Database\Seeds\Production;

use Database\Seeds\Base\Seeder as BaseSeeder;

class Seeder
	extends BaseSeeder {

	/**
	 * @inheritDoc
	 */
	public function run(): void {
		$this
			->call(UserSeeder::class)
			->call(TransactionCategorySeeder::class);
	}

}