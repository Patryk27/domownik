<?php

namespace App\Services\Search\Filters;

use App\Exceptions\InvalidOperatorException;
use Illuminate\Database\Query\Builder as QueryBuilder;

class StringFilter
	implements FilterContract {

	const
		OP_EQUALS = '=',
		OP_LIKE = 'like',
		OP_CONTAINS = 'contains';

	/**
	 * @var string
	 */
	protected $columnName;

	/**
	 * @var string
	 */
	protected $operator;

	/**
	 * @var mixed
	 */
	protected $value;

	/**
	 * @param string $columnName
	 * @param string $operator
	 * @param mixed $value
	 */
	public function __construct(
		string $columnName,
		string $operator,
		$value
	) {
		$this->columnName = $columnName;
		$this->operator = $operator;
		$this->value = $value;
	}

	/**
	 * @inheritdoc
	 */
	public function apply(QueryBuilder $builder): QueryBuilder {
		if (isset($this->value)) {
			switch (strtolower($this->operator)) {
				case self::OP_EQUALS:
				case self::OP_LIKE:
					$builder->where($this->columnName, $this->operator, $this->value);
					break;

				case self::OP_CONTAINS:
					$value = str_replace('%', '%%', $this->value);
					$builder->where($this->columnName, 'LIKE', sprintf('%%%s%%', $value));
					break;

				default:
					throw new InvalidOperatorException('Invalid operator [%s] for type [string].', $this->operator);
			}
		}

		return $builder;
	}
}