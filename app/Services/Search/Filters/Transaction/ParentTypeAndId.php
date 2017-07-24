<?php

namespace App\Services\Search\Filters\Transaction;

use App\Services\Search\Filters\FilterContract;
use Illuminate\Database\Query\Builder as QueryBuilder;

class ParentTypeAndId
	implements FilterContract {

	/**
	 * @var string
	 */
	protected $parentType;

	/**
	 * @var int
	 */
	protected $parentId;

	/**
	 * @param string $parentType
	 * @param int $parentId
	 */
	public function __construct(
		string $parentType,
		int $parentId
	) {
		$this->parentType = $parentType;
		$this->parentId = $parentId;
	}

	/**
	 * @inheritDoc
	 */
	public function apply(QueryBuilder $builder): QueryBuilder {
		return
			$builder
				->where('t.parent_type', $this->parentType)
				->where('t.parent_id', $this->parentId);
	}

}