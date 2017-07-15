@php
    /**
     * @var \App\Models\Transaction $transaction
     */
@endphp

@php
    $transactionPeriodicityTypes = \App\Models\Transaction::getPeriodicityTypes();
@endphp

{{-- Transaction periodicity type --}}
{!!
    Form::select()
        ->setIdAndName('transactionPeriodicityType')
        ->setLabel(__('views/finances/transaction/common/create-edit.transaction-periodicity-type.label'))
        ->setValueFromModel($transaction, 'periodicity_type')
        ->setRequired(true)
        ->setItems(function() use ($transactionPeriodicityTypes) {
            $items = [];

            foreach ($transactionPeriodicityTypes as $transactionPeriodicityType) {
                $items[$transactionPeriodicityType] =  __('common/transaction.periodicity-type.' . $transactionPeriodicityType);
            }

            return $items;
        })
 !!}

{{-- One shot periodicity --}}
<div id="transaction-periodicity-one-shot"
     class="transaction-periodicity"
     data-transaction-periodicity-type="{{ \App\Models\Transaction::PERIODICITY_TYPE_ONE_SHOT }}">
    <hr>

    <h5>
        {{ __('views/finances/transaction/common/create-edit.transaction-periodicity.one-shot.title') }}
    </h5>

    <div class="periodicity-wrapper">
        {{-- Calendar is loaded by the JS --}}
    </div>
</div>

{{-- Daily periodicity --}}
<div id="transaction-periodicity-daily"
     class="transaction-periodicity"
     data-transaction-periodicity-type="{{ \App\Models\Transaction::PERIODICITY_TYPE_DAILY }}">
    <hr>

    <h5>
        {{ __('views/finances/transaction/common/create-edit.transaction-periodicity.daily.title') }}
    </h5>
</div>

{{-- Weekly periodicity --}}
<div id="transaction-periodicity-weekly"
     class="transaction-periodicity"
     data-transaction-periodicity-type="{{ \App\Models\Transaction::PERIODICITY_TYPE_WEEKLY }}">
    <hr>

    <h5>
        {{ __('views/finances/transaction/common/create-edit.transaction-periodicity.weekly.title') }}
    </h5>

    <div class="form-group" data-control-name="transactionPeriodicityWeeklyDays">
        <div class="periodicity-wrapper">
            @foreach (Calendar::getWeekdaysCapitalized() as $weekDayNumber => $weekDay)
                {!!
                Form::checkbox()
                    ->setIdAndName('transactionPeriodicityWeeklyDays[]')
                    ->setLabel(__('calendar.week-days.' . $weekDay))
                    ->setValue($weekDayNumber)
                 !!}
            @endforeach
        </div>

        <div class="help-block"></div>
    </div>
</div>

{{-- Monthly periodicity --}}
<div id="transaction-periodicity-monthly"
     class="transaction-periodicity"
     data-transaction-periodicity-type="{{ \App\Models\Transaction::PERIODICITY_TYPE_MONTHLY }}">
    <hr>

    <h5>
        {{ __('views/finances/transaction/common/create-edit.transaction-periodicity.monthly.title') }}
    </h5>

    <div class="form-group" data-control-name="transactionPeriodicityMonthlyDays">
        <div class="periodicity-wrapper">
            <div class="row">
                @for($week = 1; $week <= 5; ++$week)
                    <div class="col-sm-6 col-md-2">
                        @for($day = 1; $day <= 7; ++$day)
                            @php($dayNumber = ($week - 1) * 7 + $day)
                            @if ($dayNumber <= 31)
                                {!!
                                Form::checkbox()
                                    ->setIdAndName('transactionPeriodicityMonthlyDays[]')
                                    ->setLabel($dayNumber)
                                    ->setValue($dayNumber)
                                 !!}
                            @endif
                        @endfor
                    </div>
                @endfor
            </div>
        </div>

        <div class="help-block"></div>
    </div>

    <h5 class="text-muted">
        {{ __('views/finances/transaction/common/create-edit.transaction-periodicity.monthly.warning') }}
    </h5>
</div>

{{-- Yearly periodicity --}}
<div id="transaction-periodicity-yearly"
     class="transaction-periodicity"
     data-transaction-periodicity-type="{{ \App\Models\Transaction::PERIODICITY_TYPE_YEARLY }}">
    <hr>

    <h5>
        {{ __('views/finances/transaction/common/create-edit.transaction-periodicity.yearly.title') }}
    </h5>

    <div class="periodicity-wrapper">
        {{-- Calendar is loaded by the JS --}}
    </div>
</div>

@push('scripts')
<script>
  $(function() {
      @php
          if (isset($transaction)) {
              switch ($transaction->periodicity_type) {
                  case \App\Models\Transaction::PERIODICITY_TYPE_ONE_SHOT:
                      $rows = $transaction->periodicityOneShots->map(function(\App\Models\TransactionPeriodicityOneShot $row) {
                          return sprintf('\'%s\'', $row->date->format('Y-m-d'));
                      });

                      echo sprintf('AppView.Periodicity.OneShot.prepare([%s]);', $rows->implode(','));
                      break;

                  case \App\Models\Transaction::PERIODICITY_TYPE_WEEKLY:
                      $rows = $transaction->periodicityWeeklies->map(function(\App\Models\TransactionPeriodicityWeekly $row) {
                          return $row->weekday;
                      });

                      echo sprintf('AppView.Periodicity.Weekly.prepare([%s]);', $rows->implode(','));
                      break;

                  case \App\Models\Transaction::PERIODICITY_TYPE_MONTHLY:
                      $rows = $transaction->periodicityMonthlies->map(function(\App\Models\TransactionPeriodicityMonthly $row) {
                          return $row->day;
                      });

                      echo sprintf('AppView.Periodicity.Monthly.prepare([%s]);', $rows->implode(','));
                      break;

                  case \App\Models\Transaction::PERIODICITY_TYPE_YEARLY:
                      $rows = $transaction->periodicityYearlies->map(function(\App\Models\TransactionPeriodicityYearly $row) {
                          return sprintf('[%d,%d]', $row->month, $row->day);
                      });

                      echo sprintf('AppView.Periodicity.Yearly.prepare([%s]);', $rows->implode(','));
                      break;
              }
          }
      @endphp
  });
</script>
@endpush