<?php

namespace App\Services\Transaction\Schedule;

use App\Models\TransactionSchedule;
use App\Repositories\Contracts\TransactionScheduleRepositoryContract;
use App\Services\Logger\Contract as LoggerContract;
use App\Services\Transaction\Periodicity\ParserContract as TransactionPeriodicityParserContract;
use App\Support\Facades\Date;
use Carbon\Carbon;
use Illuminate\Database\Connection as DatabaseConnection;
use Throwable;

class Updater
	implements UpdaterContract {

	/**
	 * @var LoggerContract
	 */
	protected $log;

	/**
	 * @var DatabaseConnection
	 */
	protected $db;

	/**
	 * @var TransactionScheduleRepositoryContract
	 */
	protected $transactionScheduleRepository;

	/**
	 * @var TransactionPeriodicityParserContract
	 */
	protected $transactionPeriodicityParser;

	/**
	 * @param LoggerContract $log
	 * @param DatabaseConnection $db
	 * @param TransactionScheduleRepositoryContract $transactionScheduleRepository
	 * @param TransactionPeriodicityParserContract $transactionPeriodicityParser
	 */
	public function __construct(
		LoggerContract $log,
		DatabaseConnection $db,
		TransactionScheduleRepositoryContract $transactionScheduleRepository,
		TransactionPeriodicityParserContract $transactionPeriodicityParser
	) {
		$this->log = $log;
		$this->db = $db;
		$this->transactionScheduleRepository = $transactionScheduleRepository;
		$this->transactionPeriodicityParser = $transactionPeriodicityParser;
	}

	/**
	 * @inheritDoc
	 */
	public function updateTransactionSchedule(int $transactionId): UpdaterContract {
		$this->log->info('Updating schedule for transaction with id=%d.', $transactionId);

		$today = new Carbon();

		$this->db->beginTransaction();

		try {
			$this->transactionScheduleRepository->deleteByTransactionId($transactionId);

			$this->transactionPeriodicityParser
				->reset()
				->setTransactionId($transactionId)
				->setDateRange($this->getScheduleUpdateFrom(), $this->getScheduleUpdateTo());

			$dates = $this->transactionPeriodicityParser->getRows();

			foreach ($dates as $date) {
				if ($date->lt($today)) {
					$this->log->info('-> skipping date \'%s\', because it\'s in the past.', $date->format('Y-m-d'));
					continue;
				}

				$this->log->info('-> including date: %s.', $date->format('Y-m-d'));

				(new TransactionSchedule([
					'transaction_id' => $transactionId,
					'date' => $date,
				]))->saveOrFail(); // @todo use repository
			}

			$this->db->commit();
		} catch (Throwable $ex) {
			$this->db->rollBack();
			throw $ex;
		} finally {
			$this->log->info('Cleaning up...');

			$this->transactionScheduleRepository
				->getFlushCache()
				->flush();
		}

		return $this;
	}

	/**
	 * @return Carbon
	 */
	protected function getScheduleUpdateFrom(): Carbon {
		return Date::stripTime(new Carbon('now'));
	}

	/**
	 * @return Carbon
	 */
	protected function getScheduleUpdateTo(): Carbon {
		$date = $this->getScheduleUpdateFrom();
		$date->addYear(1);

		return $date;
	}

}