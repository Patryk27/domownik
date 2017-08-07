<?php

namespace App\ValueObjects\Transaction\Category;

use App\ValueObjects\HasInitializationConstructor;
use App\ValueObjects\Transaction\Category\Tree\Item as TransactionCategoryTreeItem;
use Illuminate\Support\Collection;

class Tree {

	use HasInitializationConstructor;

	/**
	 * @var Collection|TransactionCategoryTreeItem[]
	 */
	protected $items;

	/**
	 * @return Collection|TransactionCategoryTreeItem[]
	 */
	public function getItems() {
		return $this->items;
	}

}