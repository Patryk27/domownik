<?php

namespace App\Services\Search;

use App\Services\Search\Filters\FilterContract;
use Illuminate\Database\Connection as DatabaseConnection;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Collection;

abstract class Search
	implements SearchContract {

	/**
	 * @var DatabaseConnection
	 */
	protected $db;

	/**
	 * @var QueryBuilder
	 */
	protected $builder;

	/**
	 * @param DatabaseConnection $db
	 */
	public function __construct(
		DatabaseConnection $db
	) {
		$this->db = $db;
		$this->reset();
	}

	/**
	 * @inheritdoc
	 */
	public function reset() {
		$this->builder = $this->db->query();
		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function get(): Collection {
		return $this->builder->get();
	}

	/**
	 * @inheritdoc
	 */
	public function getQueryBuilder(): QueryBuilder {
		return $this->builder;
	}

	/**
	 * @inheritdoc
	 */
	protected function applyFilter(FilterContract $filter) {
		$filter->apply($this->builder);
		return $this;
	}

}