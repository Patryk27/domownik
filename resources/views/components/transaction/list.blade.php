@php
    /**
      * @var \Illuminate\Support\Collection|\App\ValueObjects\ScheduledTransaction[] $transactions
      * @var \App\ValueObjects\View\Components\Transaction\CList\Options|null $options
      */
@endphp

<p>
    {!! Lang::choice(__('models/transaction.misc.found-count', [
        'count' => $transactions->count(),
    ]), $transactions->count()) !!}
</p>

{{-- @todo set options below in the $options (or use some $optionsOverride variable) --}}
{{--
'transactionButtons' => ['edit', 'edit-parent'],
'showCounter' => true,
--}}

@if ($transactions->isNotEmpty())
    @include('components.transaction.list.partial', [
        'transactions' => $transactions,
        'options' => $options,
    ])
@endif