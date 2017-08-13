@php
    /**
      * @var \Illuminate\Support\Collection|\App\ValueObjects\ScheduledTransaction[] $transactions
      * @var string[] $transactionButtons
      */
@endphp

<p>
    {!! Lang::choice(__('models/transaction.misc.found-count', [
        'count' => $transactions->count(),
    ]), $transactions->count()) !!}
</p>

@if ($transactions->isNotEmpty())
    @include('components.transaction.list.compact', [
        'transactions' => $transactions,
        'transactionButtons' => ['edit', 'edit-parent'],
        'showCounter' => true,
    ])
@endif