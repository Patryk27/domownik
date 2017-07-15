@php
    /**
      * @var \App\ValueObjects\ScheduledTransaction[] $transactions
      * @var string[] $transactionButtons
      */
@endphp

<p>
    @php($transactionCount = count($transactions))

    {!! Lang::choice(__('components/transaction-list.found-transaction-count', [
        'transactionCount' => $transactionCount,
    ]), $transactionCount) !!}
</p>

@include('components.transaction-list.compact', [
    'transactions' => $transactions,
    'transactionButtons' => ['edit', 'edit-parent'],
    'showCounter' => true,
])