<?php

use App\Exceptions\Exception;
use App\Models\Budget;
use App\Models\Transaction;
use App\Models\TransactionValueConstant;
use App\Models\TransactionValueRange;
use App\Services\Logger\Contract as LoggerContract;
use App\Services\Transaction\Schedule\ProcessorContract as TransactionScheduleProcessorServiceContract;
use App\Services\Transaction\Schedule\UpdaterContract as TransactionScheduleUpdaterServiceContract;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class BudgetTransactionSeeder
	extends Seeder {

	/**
	 * @var LoggerContract
	 */
	protected $log;

	/**
	 * @var TransactionScheduleProcessorServiceContract
	 */
	protected $transactionScheduleProcessorService;

	/**
	 * @var TransactionScheduleUpdaterServiceContract
	 */
	protected $transactionScheduleUpdaterService;

	/**
	 * @var Budget
	 */
	protected $firstBudget;

	/**
	 * @param LoggerContract $log
	 * @param TransactionScheduleProcessorServiceContract $transactionScheduleProcessorService
	 * @param TransactionScheduleUpdaterServiceContract $transactionScheduleUpdaterService
	 */
	public function __construct(
		LoggerContract $log,
		TransactionScheduleProcessorServiceContract $transactionScheduleProcessorService,
		TransactionScheduleUpdaterServiceContract $transactionScheduleUpdaterService
	) {
		$this->log = $log;
		$this->transactionScheduleProcessorService = $transactionScheduleProcessorService;
		$this->transactionScheduleUpdaterService = $transactionScheduleUpdaterService;
	}

	/**
	 * @return void
	 */
	public function run() {
		$this->prepare();

		$transactionCount = 500;

		for ($i = 1; $i <= $transactionCount; ++$i) {
			$this->log->info('Creating transaction %d of %d...', $i, $transactionCount);
			$this->createNewTransaction($i, 'budget', $this->firstBudget->id);
		}

		$this->log->info('Processing transaction schedule...');
		$this->transactionScheduleProcessorService->processSchedule();

		$this->log->info('Flushing cache...');
		Cache::flush();
	}

	/**
	 * @return $this
	 * @throws Exception
	 */
	protected function prepare() {
		$this->firstBudget =
			Budget::where('name', 'First budget')
				  ->first();

		if (empty($this->firstBudget)) {
			throw new Exception('Budget with name \'First budget\' could not have been found.');
		}

		return $this;
	}

	/**
	 * @param int $captionId
	 * @param string $parentType
	 * @param int $parentId
	 * @return $this
	 */
	protected function createNewTransaction(int $captionId, string $parentType, int $parentId) {
		$date = new Carbon('now');
		$date->addDays($this->randomNumber(-2 * 365, 2 * 365));

		// create transaction
		$transaction = new Transaction();
		$transaction->parent_type = $parentType;
		$transaction->parent_id = $parentId;
		$transaction->type = $this->randomItem([Transaction::TYPE_INCOME, Transaction::TYPE_EXPENSE]);
		$transaction->value_type = $this->randomItem([Transaction::VALUE_TYPE_CONSTANT, Transaction::VALUE_TYPE_RANGE]);
		$transaction->periodicity_type = Transaction::PERIODICITY_TYPE_ONE_SHOT;
		$transaction->name = sprintf('Test transaction #%d', $captionId);

		// create transaction value
		switch ($transaction->value_type) {
			case Transaction::VALUE_TYPE_CONSTANT:
				$transactionValue = new TransactionValueConstant();
				$transactionValue->value = $this->randomPrice(10, 5000);
				$transactionValue->saveOrFail();

				$transactionValue
					->transaction()
					->save($transaction);

				break;

			case Transaction::VALUE_TYPE_RANGE:
				$transactionValue = new TransactionValueRange();
				$transactionValue->value_from = $this->randomPrice(10, 5000);
				$transactionValue->value_to = $transactionValue->value_from + $this->randomPrice(10, 5000);
				$transactionValue->saveOrfail();

				$transactionValue
					->transaction()
					->save($transaction);

				break;
		}

		// create transaction periodicity
		$transaction
			->periodicityOneShots()
			->create([
				'date' => $date,
			]);

		$transaction->save();

		$this->transactionScheduleUpdaterService->updateTransactionSchedule($transaction->id);

		return $this;
	}

	/**
	 * @param int $min
	 * @param int $max
	 * @return int
	 */
	protected function randomNumber($min, $max) {
		return mt_rand($min, $max);
	}

	/**
	 * @param mixed[] $items
	 * @return mixed
	 */
	protected function randomItem($items) {
		if (empty($items)) {
			return null;
		}

		return $items[$this->randomNumber(0, count($items) - 1)];
	}

	/**
	 * @param float $min
	 * @param float $max
	 * @return float
	 */
	protected function randomPrice($min, $max) {
		return $this->randomNumber($min * 100, $max * 100) / 100.0;
	}

}
