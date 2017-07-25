@php
    /**
     * @var \App\Models\Transaction $transaction
     */
@endphp

{{-- Transaction periodicity type --}}
<div class="form-group required">
    {!! Form::label('periodicity_type', __('views/finances/transactions/create-edit.transaction-periodicity-type.label')) !!}
    {!! Form::select('periodicity_type', \App\Models\Transaction::getPeriodicityTypesSelect(), null, [
        'class' => 'form-control',
    ]) !!}
</div>

@include('views.finances.transactions.create-edit.form.periodicity.one-shot')
@include('views.finances.transactions.create-edit.form.periodicity.daily')
@include('views.finances.transactions.create-edit.form.periodicity.weekly')
@include('views.finances.transactions.create-edit.form.periodicity.monthly')
@include('views.finances.transactions.create-edit.form.periodicity.yearly')

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