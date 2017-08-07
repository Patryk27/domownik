@php
    /**
      * @var \App\ValueObjects\ScheduledTransaction[] $transactions
      * @var string[] $transactionButtons
      */
@endphp

<p>
    @php($transactionCount = count($transactions))

        {!! Lang::choice(__('models/transaction.misc.found-count', [
            'count' => $transactionCount,
        ]), $transactionCount) !!}
</p>

@include('components.transaction.list.compact', [
    'transactions' => $transactions,
    'transactionButtons' => ['edit', 'edit-parent'],
    'showCounter' => true,
])