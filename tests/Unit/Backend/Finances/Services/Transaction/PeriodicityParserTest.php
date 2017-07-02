<?php

use App\Modules\Finances\Models\Transaction;
use App\Modules\Finances\Models\TransactionPeriodicityMonthly;
use App\Modules\Finances\Models\TransactionPeriodicityOneShot;
use App\Modules\Finances\Models\TransactionPeriodicityWeekly;
use App\Modules\Finances\Models\TransactionPeriodicityYearly;
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
	 * Checks if OneShotMatcher works correctly.
	 */
	public function testOneShotPeriodicity() {
		$transaction = new Transaction();
		$transaction->id = 1;
		$transaction->periodicity_type = Transaction::PERIODICITY_TYPE_ONE_SHOT;

		$transactionPeriodicities = new Collection([
			new TransactionPeriodicityOneShot(['date' => '2010-01-01']),
			new TransactionPeriodicityOneShot(['date' => '2010-02-04']),
			new TransactionPeriodicityOneShot(['date' => '2010-03-06']),
			new TransactionPeriodicityOneShot(['date' => '2010-04-08']),
			new TransactionPeriodicityOneShot(['date' => '2010-05-10']),
			new TransactionPeriodicityOneShot(['date' => '2010-06-12']),
		]);

		$this->transactionRepositoryMock
			->method('getOrFail')
			->with(1)
			->willReturn($transaction);

		$this->transactionPeriodicityRepositoryMock
			->method('getOneShotsByTransactionId')
			->with(1)
			->willReturn($transactionPeriodicities);

		$periodicityParser = new PeriodicityParser($this->transactionRepositoryMock, $this->transactionPeriodicityRepositoryMock);
		$periodicityParser
			->setTransactionId(1)
			->setDateRange(new Carbon('2010-01-01'), new Carbon('2010-03-06'));

		$rows = $periodicityParser->getRows();

		$this->assertCount(3, $rows);

		$this->assertRow($rows[0], 2010, 1, 1);
		$this->assertRow($rows[1], 2010, 2, 4);
		$this->assertRow($rows[2], 2010, 3, 6);
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
	 * Checks if WeeklyMatcher works correctly.
	 */
	public function testWeeklyPeriodicity() {
		$transaction = new Transaction();
		$transaction->id = 1;
		$transaction->periodicity_type = Transaction::PERIODICITY_TYPE_WEEKLY;

		$transactionPeriodicities = new Collection([
			new TransactionPeriodicityWeekly(['weekday' => 1]),
			new TransactionPeriodicityWeekly(['weekday' => 4]),
			new TransactionPeriodicityWeekly(['weekday' => 7]),
		]);

		$this->transactionRepositoryMock
			->method('getOrFail')
			->with(1)
			->willReturn($transaction);

		$this->transactionPeriodicityRepositoryMock
			->method('getWeekliesByTransactionId')
			->with(1)
			->willReturn($transactionPeriodicities);

		$periodicityParser = new PeriodicityParser($this->transactionRepositoryMock, $this->transactionPeriodicityRepositoryMock);
		$periodicityParser
			->setTransactionId(1)
			->setDateRange(new Carbon('2009-12-01'), new Carbon('2010-01-10'));

		$rows = $periodicityParser->getRows();

		$this->assertCount(17, $rows);

		$this->assertRow($rows[0], 2009, 12, 3);
		$this->assertRow($rows[1], 2009, 12, 6);
		$this->assertRow($rows[2], 2009, 12, 7);
		$this->assertRow($rows[3], 2009, 12, 10);
		$this->assertRow($rows[4], 2009, 12, 13);
		$this->assertRow($rows[5], 2009, 12, 14);
		$this->assertRow($rows[6], 2009, 12, 17);
		$this->assertRow($rows[7], 2009, 12, 20);
		$this->assertRow($rows[8], 2009, 12, 21);
		$this->assertRow($rows[9], 2009, 12, 24);
		$this->assertRow($rows[10], 2009, 12, 27);
		$this->assertRow($rows[11], 2009, 12, 28);
		$this->assertRow($rows[12], 2009, 12, 31);
		$this->assertRow($rows[13], 2010, 1, 3);
		$this->assertRow($rows[14], 2010, 1, 4);
		$this->assertRow($rows[15], 2010, 1, 7);
		$this->assertRow($rows[16], 2010, 1, 10);
	}

	/**
	 * Checks if MonthlyMatcher works correctly.
	 */
	public function testMonthlyPeriodicity() {
		$transaction = new Transaction();
		$transaction->id = 1;
		$transaction->periodicity_type = Transaction::PERIODICITY_TYPE_MONTHLY;

		$transactionPeriodicities = new Collection([
			new TransactionPeriodicityMonthly(['day' => 1]),
			new TransactionPeriodicityMonthly(['day' => 7]),
			new TransactionPeriodicityMonthly(['day' => 20]),
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
	 * Checks if YearlyMatcher works correctly.
	 */
	public function testYearlyPeriodicity() {
		$transaction = new Transaction();
		$transaction->id = 1;
		$transaction->periodicity_type = Transaction::PERIODICITY_TYPE_YEARLY;

		$transactionPeriodicities = new Collection([
			new TransactionPeriodicityYearly(['month' => 1, 'day' => 1]),
			new TransactionPeriodicityYearly(['month' => 2, 'day' => 4]),
			new TransactionPeriodicityYearly(['month' => 3, 'day' => 6]),
			new TransactionPeriodicityYearly(['month' => 4, 'day' => 8]),
			new TransactionPeriodicityYearly(['month' => 5, 'day' => 10]),
			new TransactionPeriodicityYearly(['month' => 6, 'day' => 12]),
		]);

		$this->transactionRepositoryMock
			->method('getOrFail')
			->with(1)
			->willReturn($transaction);

		$this->transactionPeriodicityRepositoryMock
			->method('getYearliesByTransactionId')
			->with(1)
			->willReturn($transactionPeriodicities);

		$periodicityParser = new PeriodicityParser($this->transactionRepositoryMock, $this->transactionPeriodicityRepositoryMock);
		$periodicityParser
			->setTransactionId(1)
			->setDateRange(new Carbon('2009-12-01'), new Carbon('2010-03-06'));

		$rows = $periodicityParser->getRows();

		$this->assertCount(3, $rows);

		$this->assertRow($rows[0], 2010, 1, 1);
		$this->assertRow($rows[1], 2010, 2, 4);
		$this->assertRow($rows[2], 2010, 3, 6);
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