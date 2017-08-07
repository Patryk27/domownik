<?php

namespace App\Services\Search\Filters;

use Illuminate\Database\Query\Builder as QueryBuilder;

interface FilterContract {

	/**
	 * Applies filter to given query builder.
	 * @param QueryBuilder $builder
	 * @return QueryBuilder
	 */
	public function apply(QueryBuilder $builder): QueryBuilder;

}