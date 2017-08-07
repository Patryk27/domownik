<?php

namespace App\ValueObjects\Transaction\Category\Tree;

use App\ValueObjects\HasInitializationConstructor;
use App\ValueObjects\Transaction\Category\Tree as TransactionCategoryTree;

class Item {

	use HasInitializationConstructor;

	/**
	 * @var int
	 */
	protected $id;

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var TransactionCategoryTree
	 */
	protected $children;

	/**
	 * @return int
	 */
	public function getId(): int {
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}

	/**
	 * @return TransactionCategoryTree
	 */
	public function getChildren(): TransactionCategoryTree {
		return $this->children;
	}

}