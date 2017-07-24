<?php

namespace App\Services\Search\Filters\Common;

use App\Exceptions\InvalidOperatorException;
use App\Services\Search\Filters\FilterContract;
use Carbon\Carbon;
use DateTime;
use Illuminate\Database\Query\Builder as QueryBuilder;

class Date
	implements FilterContract {

	/**
	 * @var string
	 */
	protected $columnName;

	/**
	 * @var string
	 */
	protected $operator;

	/**
	 * @var string|DateTime|Carbon
	 */
	protected $value;

	/**
	 * @param string $columnName
	 * @param string $operator
	 * @param $value
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
			switch ($this->operator) {
				case '<':
				case '<=':
				case '=':
				case '>=':
				case '>':
				case '<>':
					$builder->where($this->columnName, $this->operator, $this->value);
					break;

				case 'between':
					if (!is_array($this->value)) {
						throw new InvalidOperatorException('Expected an array value for operator [between].');
					}
					
					$builder->whereBetween($this->columnName, $this->value[0], $this->value[1]);
					break;

				default:
					throw new InvalidOperatorException('Invalid operator [%s] for type [date].', $this->operator);
			}
		}

		return $builder;
	}
}