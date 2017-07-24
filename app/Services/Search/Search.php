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
	 * @var Collection|FilterContract[]
	 */
	protected $filters;

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
		$this->filters = new Collection();

		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function addFilter(FilterContract $filter) {
		$this->filters->push($filter);
		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function get(): Collection {
		$this->applyFilters();

		return $this->builder->get(['*']);
	}

	/**
	 * @inheritdoc
	 */
	public function getQueryBuilder(): QueryBuilder {
		return $this->builder;
	}

	/**
	 * @return $this
	 */
	protected function applyFilters() {
		foreach ($this->filters as $filter) {
			$this->builder = $filter->apply($this->builder);
		}

		return $this;
	}

}