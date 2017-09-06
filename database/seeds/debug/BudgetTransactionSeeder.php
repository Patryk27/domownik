<?php

namespace Database\Seeds\Debug;

use App\Models\Budget;
use App\Models\Transaction;
use App\Models\TransactionValueConstant;
use App\Models\TransactionValueRange;
use App\Services\Transaction\Schedule\ProcessorContract as TransactionScheduleProcessorContract;
use App\Services\Transaction\Schedule\UpdaterContract as TransactionScheduleUpdaterContract;
use Carbon\Carbon;
use Faker\Generator as FakerGenerator;
use Illuminate\Cache\Repository as CacheRepository;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Symfony\Component\Console\Helper\ProgressBar as ConsoleProgressBarHelper;

class BudgetTransactionSeeder
	extends Seeder {

	const TRANSACTION_COUNT = 500;

	/**
	 * @var FakerGenerator
	 */
	protected $faker;

	/**
	 * @var CacheRepository
	 */
	protected $cache;

	/**
	 * @var TransactionScheduleProcessorContract
	 */
	protected $transactionScheduleProcessor;

	/**
	 * @var TransactionScheduleUpdaterContract
	 */
	protected $transactionScheduleUpdater;

	/**
	 * @var Collection|Budget[]
	 */
	protected $budgets;

	/**
	 * @param FakerGenerator $faker
	 * @param CacheRepository $cache
	 * @param TransactionScheduleProcessorContract $transactionScheduleProcessor
	 * @param TransactionScheduleUpdaterContract $transactionScheduleUpdater
	 */
	public function __construct(
		FakerGenerator $faker,
		CacheRepository $cache,
		TransactionScheduleProcessorContract $transactionScheduleProcessor,
		TransactionScheduleUpdaterContract $transactionScheduleUpdater
	) {
		$this->faker = $faker;
		$this->cache = $cache;
		$this->transactionScheduleProcessor = $transactionScheduleProcessor;
		$this->transactionScheduleUpdater = $transactionScheduleUpdater;
	}

	/**
	 * @return void
	 */
	public function run() {
		$console = $this->command->getOutput();

		// prepare budgets to which we'll be assigning transactions to
		$this->budgets = Budget::all();

		// create transactions
		$console->writeln('Creating transactions...');

		$progressBar = new ConsoleProgressBarHelper($console);
		$progressBar->setFormat('very_verbose');
		$progressBar->start(self::TRANSACTION_COUNT);

		for ($i = 1; $i <= self::TRANSACTION_COUNT; ++$i) {
			$budget = $this->budgets->random();

			$this->createNewTransaction(Transaction::PARENT_TYPE_BUDGET, $budget->id);
			$progressBar->advance();
		}

		$progressBar->finish();
		$console->writeln('');

		// process transaction schedule
		$console->writeln('Processing transaction schedule...');
		$this->transactionScheduleProcessor->processSchedule();
	}

	/**
	 * @param string $parentType
	 * @param int $parentId
	 * @return $this
	 */
	protected function createNewTransaction(string $parentType, int $parentId) {
		$date = new Carbon();
		$date->addDays(mt_rand(-2 * 365, 2 * 365));

		// create transaction
		$transaction = new Transaction([
			'parent_type' => $parentType,
			'parent_id' => $parentId,
			'type' => Arr::random([Transaction::TYPE_INCOME, Transaction::TYPE_EXPENSE]),

			'name' => $this->faker->words(3, true),
			'description' => Arr::random(['', $this->faker->realText(200)]),

			'value_type' => Arr::random([Transaction::VALUE_TYPE_CONSTANT, Transaction::VALUE_TYPE_RANGE]),
			'periodicity_type' => Transaction::PERIODICITY_TYPE_ONE_SHOT,
		]);

		// create transaction value
		switch ($transaction->value_type) {
			case Transaction::VALUE_TYPE_CONSTANT:
				$transactionValue = new TransactionValueConstant([
					'value' => mt_rand(10, 5000),
				]);

				$transactionValue->saveOrFail();

				$transactionValue
					->transaction()
					->save($transaction);

				break;

			case Transaction::VALUE_TYPE_RANGE:
				$valueFrom = mt_rand(10, 5000);

				$transactionValue = new TransactionValueRange([
					'value_from' => $valueFrom,
					'value_to' => $valueFrom + mt_rand(10, 5000),
				]);

				$transactionValue->saveOrFail();

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

		$transaction->saveOrFail();

		// update schedule
		$this->transactionScheduleUpdater->updateTransactionSchedule($transaction->id);

		return $this;
	}

}
