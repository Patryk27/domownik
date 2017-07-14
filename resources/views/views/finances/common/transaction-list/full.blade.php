@php
    /**
      * @var \App\Modules\Finances\ValueObjects\ScheduledTransaction[] $transactions
      * @var string[] $transactionButtons
      */
@endphp

<p>
    @php($transactionCount = count($transactions))

    {!! Lang::choice(__('Finances::common/transaction-list.found-transaction-count', [
        'transactionCount' => $transactionCount,
    ]), $transactionCount) !!}
</p>

@include('Finances::common.transaction-list.compact', [
    'transactions' => $transactions,
    'transactionButtons' => ['edit', 'edit-parent'],
    'showCounter' => true,
])