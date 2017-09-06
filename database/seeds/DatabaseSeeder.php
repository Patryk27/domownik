<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder
	extends Seeder {

	/**
	 * @return void
	 */
	public function run() {
		$env = $this->command->option('env') ?? env('APP_ENV');

		$this->command->info(sprintf('Seeding for environment: [%s].', $env));

		/**
		 * @var \Database\Seeds\Base\Seeder[] $seeders
		 */
		$seeders = [
			'debug' => $this->container->build(\Database\Seeds\Debug\Seeder::class),
			'production' => $this->container->build(\Database\Seeds\Production\Seeder::class),
		];

		if (!isset($seeders[$env])) {
			throw new Exception(sprintf('Found no seeders for environment: [%s].', $env));
		}

		$seeders[$env]
			->setCommand($this->command)
			->run();
	}

}
