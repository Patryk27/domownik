<?php

namespace Database\Seeds\Production;

use App\Models\TransactionCategory;
use Illuminate\Cache\Repository as CacheRepository;
use Illuminate\Database\Connection as DatabaseConnection;
use Illuminate\Database\Seeder;

class TransactionCategorySeeder
	extends Seeder {

	/**
	 * @var DatabaseConnection
	 */
	protected $db;

	/**
	 * @var CacheRepository
	 */
	protected $cache;

	/**
	 * @param DatabaseConnection $db
	 * @param CacheRepository $cache
	 */
	public function __construct(
		DatabaseConnection $db,
		CacheRepository $cache
	) {
		$this->db = $db;
		$this->cache = $cache;
	}

	/**
	 * @return void
	 */
	public function run() {
		$this->db->table('transaction_categories')->delete();

		$this->createTree(null, [
			__('seeders/transaction-category.incomes') => [
				__('seeders/transaction-category.incomes.work'),
			],

			__('seeders/transaction-category.expenses') => [
				__('seeders/transaction-category.expenses.for-home'),
				__('seeders/transaction-category.expenses.for-living'),
			],
		]);

		$this->cache->flush();
	}

	/**
	 * @param TransactionCategory|null $parent
	 * @param array $tree
	 * @return void
	 */
	protected function createTree(?TransactionCategory $parent, array $tree): void {
		foreach ($tree as $key => $value) {
			if (is_array($value)) {
				$this->createTree(
					$this->createCategory($parent, $key),
					$value
				);
			} else {
				$this->createCategory($parent, $value);
			}
		}
	}

	/**
	 * @param TransactionCategory|null $parent
	 * @param string $name
	 * @return TransactionCategory
	 */
	protected function createCategory(?TransactionCategory $parent, string $name): TransactionCategory {
		$category = new TransactionCategory([
			'name' => $name,
			'parent_category_id' => isset($parent) ? $parent->id : null,
		]);

		$category->saveOrFail();

		return $category;
	}

}