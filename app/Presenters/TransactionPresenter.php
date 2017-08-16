<?php

namespace App\Presenters;

use App\Models\Transaction;
use App\ValueObjects\View\Components\Transaction\CList\Options as TransactionListOptions;
use Carbon\Carbon;
use Illuminate\Support\Collection;

/**
 * @property Transaction $model
 */
class TransactionPresenter
	extends AbstractPresenter {

	/**
	 * @return string
	 */
	public function getEditUrl(): string {
		return route('finances.transactions.edit', $this->model->id);
	}

	/**
	 * @return string
	 */
	public function getParentEditUrl(): string {
		return route('finances.transactions.edit', $this->model->parent_transaction_id);
	}

	/**
	 * @param Carbon $date
	 * @param TransactionListOptions $listOptions
	 * @return string
	 */
	public function getRowClasses(Carbon $date, TransactionListOptions $listOptions): string {
		$result = new Collection();

		if ($listOptions->getHighlightFuture()) {
			if ($date->isFuture()) {
				$result[] = 'future-transaction';
			}
		}

		return $result->implode(' ');
	}

}