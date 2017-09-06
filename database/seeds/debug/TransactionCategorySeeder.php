<?php

namespace Database\Seeds\Debug;

use App\Models\TransactionCategory;
use Faker\Generator as FakerGenerator;
use Illuminate\Cache\Repository as CacheRepository;
use Illuminate\Database\Connection as DatabaseConnection;
use Illuminate\Database\Seeder;

class TransactionCategorySeeder
	extends Seeder {

	const TRANSACTION_CATEGORY_COUNT = 10;

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
		$this->db->table('transaction_categories')->delete();

		for ($i = 1; $i <= self::TRANSACTION_CATEGORY_COUNT; ++$i) {
			// make each of the categories have some random parent
			if ($i > 1 && mt_rand(0, 2) <= 1) {
				$parentCategoryId = TransactionCategory::all()->random()->id;
			} else {
				$parentCategoryId = null;
			}

			(new TransactionCategory([
				'name' => $this->faker->words(3, true),
				'parent_category_id' => $parentCategoryId,
			]))->save();
		}

		$this->cache->flush();
	}

}