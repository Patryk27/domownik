<?php

namespace Database\Seeds\Debug;

use App\Models\Budget;
use Faker\Generator as FakerGenerator;
use Illuminate\Cache\Repository as CacheRepository;
use Illuminate\Database\Connection as DatabaseConnection;
use Illuminate\Database\Seeder;

class BudgetSeeder
	extends Seeder {

	const BUDGET_COUNT = 3;

	/**
	 * @var FakerGenerator
	 */
	protected $faker;

	/**
	 * @var DatabaseConnection
	 */
	protected $db;

	/**
	 * @var CacheRepository
	 */
	protected $cache;

	/**
	 * @param FakerGenerator $faker
	 * @param DatabaseConnection $db
	 * @param CacheRepository $cache
	 */
	public function __construct(
		FakerGenerator $faker,
		DatabaseConnection $db,
		CacheRepository $cache
	) {
		$this->faker = $faker;
		$this->db = $db;
		$this->cache = $cache;
	}

	/**
	 * @return void
	 */
	public function run() {
		$this->db->table('budgets')->delete();

		for ($i = 1; $i <= self::BUDGET_COUNT; ++$i) {
			(new Budget([
				'type' => Budget::TYPE_REGULAR,
				'name' => $this->faker->words(3, true),
				'description' => $this->faker->realText(200),
				'status' => Budget::STATUS_ACTIVE,
			]))->save();
		}

		$this->cache->flush();
	}

}
