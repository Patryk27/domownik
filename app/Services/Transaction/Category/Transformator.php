<?php

namespace App\Services\Transaction\Category;

use App\Models\TransactionCategory;
use App\Repositories\Contracts\TransactionCategoryRepositoryContract;
use App\ValueObjects\Transaction\Category\Tree as TransactionCategoryTree;
use App\ValueObjects\Transaction\Category\Tree\Item as TransactionCategoryTreeItem;
use Illuminate\Support\Collection;

class Transformator
	implements TransformatorContract {

	/**
	 * @var TransactionCategoryRepositoryContract
	 */
	protected $transactionCategoryRepository;

	/**
	 * @param TransactionCategoryRepositoryContract $transactionCategoryRepository
	 */
	public function __construct(
		TransactionCategoryRepositoryContract $transactionCategoryRepository
	) {
		$this->transactionCategoryRepository = $transactionCategoryRepository;
	}

	/**
	 * @inheritDoc
	 */
	public function convertListToTree(Collection $categories): TransactionCategoryTree {
		return new TransactionCategoryTree([
			'items' => $categories->map(function (TransactionCategory $transactionCategory) {
				$children = $this->transactionCategoryRepository->getChildren($transactionCategory->id);

				return new TransactionCategoryTreeItem([
					'id' => $transactionCategory->id,
					'name' => $transactionCategory->name,
					'children' => $this->convertListToTree($children),
				]);
			}),
		]);
	}

	/**
	 * @inheritDoc
	 */
	public function convertTreeToJsTree(TransactionCategoryTree $categoryTree): array {
		$categories = $categoryTree->getItems();

		return $categories
			->map(function (TransactionCategoryTreeItem $category) {
				return [
					'id' => $category->getId(),
					'text' => $category->getName(),
					'children' => $this->convertTreeToJsTree($category->getChildren()),
				];
			})
			->all();
	}
}