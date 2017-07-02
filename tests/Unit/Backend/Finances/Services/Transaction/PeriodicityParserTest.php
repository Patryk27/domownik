<?php

use App\Modules\Finances\Models\Transaction;
use App\Modules\Finances\Models\TransactionPeriodicityMonthly;
use App\Modules\Finances\Repositories\Contracts\TransactionPeriodicityRepositoryContract;
use App\Modules\Finances\Repositories\Contracts\TransactionRepositoryContract;
use App\Modules\Finances\Services\Transaction\PeriodicityParser;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Tests\Unit\TestCase;

class PeriodicityParserTest
	extends TestCase {

	/**
	 * @var TransactionRepositoryContract|PHPUnit_Framework_MockObject_MockObject
	 */
	protected $transactionRepositoryMock;

	/**
	 * @var TransactionPeriodicityRepositoryContract|PHPUnit_Framework_MockObject_MockObject
	 */
	protected $transactionPeriodicityRepositoryMock;

	/**
	 * @return void
	 */
	public function setUp() {
		parent::setUp();

		$this->transactionRepositoryMock = $this->createMock(TransactionRepositoryContract::class);
		$this->transactionPeriodicityRepositoryMock = $this->createMock(TransactionPeriodicityRepositoryContract::class);
	}

	/**
	 * Checks if DailyMatcher works correctly.
	 */
	public function testDailyPeriodicity() {
		$transaction = new Transaction();
		$transaction->id = 1;
		$transaction->periodicity_type = Transaction::PERIODICITY_TYPE_DAILY;

		$this->transactionRepositoryMock
			->method('getOrFail')
			->with(1)
			->willReturn($transaction);

		$periodicityParser = new PeriodicityParser($this->transactionRepositoryMock, $this->transactionPeriodicityRepositoryMock);
		$periodicityParser
			->setTransactionId(1)
			->setDateRange(new Carbon('2009-12-30'), new Carbon('2010-01-10'));

		$rows = $periodicityParser->getRows();

		$this->assertCount(12, $rows);

		$this->assertRow($rows[0], 2009, 12, 30);
		$this->assertRow($rows[1], 2009, 12, 31);

		for ($dayNumber = 1; $dayNumber < 11; ++$dayNumber) {
			$row = $rows[$dayNumber + 1];
			$this->assertRow($row, 2010, 1, $dayNumber);
		}
	}

	/**
	 * Checks if MonthlyMatcher works correctly.
	 */
	public function testMonthlyPeriodicity() {
		$transaction = new Transaction();
		$transaction->id = 1;
		$transaction->periodicity_type = Transaction::PERIODICITY_TYPE_MONTHLY;

		$transactionPeriodicities = new Collection([
			new TransactionPeriodicityMonthly(['day_number' => 1]),
			new TransactionPeriodicityMonthly(['day_number' => 7]),
			new TransactionPeriodicityMonthly(['day_number' => 20]),
		]);

		$this->transactionRepositoryMock
			->method('getOrFail')
			->with(1)
			->willReturn($transaction);

		$this->transactionPeriodicityRepositoryMock
			->method('getMonthliesByTransactionId')
			->with(1)
			->willReturn($transactionPeriodicities);

		$periodicityParser = new PeriodicityParser($this->transactionRepositoryMock, $this->transactionPeriodicityRepositoryMock);
		$periodicityParser
			->setTransactionId(1)
			->setDateRange(new Carbon('2009-12-01'), new Carbon('2010-01-10'));

		$rows = $periodicityParser->getRows();

		$this->assertCount(5, $rows);

		$this->assertRow($rows[0], 2009, 12, 1);
		$this->assertRow($rows[1], 2009, 12, 7);
		$this->assertRow($rows[2], 2009, 12, 20);
		$this->assertRow($rows[3], 2010, 1, 1);
		$this->assertRow($rows[4], 2010, 1, 7);
	}

	/**
	 * @param Carbon $row
	 * @param int $year
	 * @param int $month
	 * @param int $day
	 * @return PeriodicityParserTest
	 */
	protected function assertRow($row, int $year, int $month, int $day): self {
		/**
		 * @var Carbon $row
		 */

		$this->assertInstanceOf(Carbon::class, $row);
		$this->assertEquals($row->format('Y-m-d'), sprintf('%04d-%02d-%02d', $year, $month, $day));

		return $this;
	}

}