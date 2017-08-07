<?php

namespace App\Services\Transaction\Category;

use App\Models\TransactionCategory;
use App\ValueObjects\Transaction\Category\Tree as TransactionCategoryTree;
use Illuminate\Support\Collection;

interface TransformatorContract {

	/**
	 * Converts flat category list to a tree.
	 * @param Collection|TransactionCategory[] $categories
	 * @return TransactionCategoryTree
	 */
	public function convertListToTree(Collection $categories): TransactionCategoryTree;

	/**
	 * Converts category tree to a structure which can be passed to the jsTree.
	 * @param TransactionCategoryTree $categoryTree
	 * @return array
	 */
	public function convertTreeToJsTree(TransactionCategoryTree $categoryTree): array;

}