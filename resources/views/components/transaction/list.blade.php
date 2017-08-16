@php
    /**
      * @var \Illuminate\Support\Collection|\App\ValueObjects\ScheduledTransaction[] $transactions
      * @var \App\ValueObjects\View\Components\Transaction\CList\Options|null $options
      */
@endphp

@php
    if (is_array($options)) {
        $options = new \App\ValueObjects\View\Components\Transaction\CList\Options($options);
    }
@endphp

@if ($options->getShowCounter())
    <p>
        {!! Lang::choice(__('models/transaction.misc.found-count', [
            'count' => $transactions->count(),
        ]), $transactions->count()) !!}
    </p>
@endif

@if ($transactions->isNotEmpty())
    @if ($transactions->isEmpty())
        <p class="no-data">
            {{ __('models/transaction.misc.found-none') }}
        </p>
    @else
        <table class="table table-hover table-striped transaction-list">
            <thead>
            <tr>
                @if ($options->getShowRowCounter())
                    <th>{{ __('components/table.row-counter') }}</th>
                @endif

                <th>{{ __('models/transaction.fields.date') }}</th>
                <th>{{ __('models/transaction.fields.name') }}</th>
                <th>{{ __('models/transaction.fields.value') }}</th>

                @if ($options->hasButtons())
                    <th></th>
                @endif
            </tr>
            </thead>
            <tbody>
            @foreach ($transactions->all() as $itemId => $item)
                @php
                    if ($item instanceof \App\ValueObjects\ScheduledTransaction) {
                        $transaction = $item->getTransaction();
                        $date = $item->getDate();
                    } else {
                        $transaction = $item;
                        $date = $item->periodicity->date;
                    }

                    $transactionPresenter = $transaction->getPresenter();
                @endphp
                <tr class="{{ $transactionPresenter->getRowClasses($date, $options) }}">
                    {{-- Row counter --}}
                    @if ($options->getShowRowCounter())
                        <td>{{ $itemId + 1 }}</td>
                    @endif

                    {{-- Transaction date --}}
                    <td>{{ Date::format('%Y-%m-%e %a', $date) }}</td>

                    {{-- Transaction name --}}
                    <td>{{ $transaction->name }}</td>

                    {{-- Transaction value --}}
                    <td>
                        @include('components.transaction.value', [
                            'transaction' => $transaction,
                        ])
                    </td>

                    {{-- Action buttons --}}
                    @if ($options->hasButtons())
                        <td class="transaction-list-buttons">
                            @if ($options->hasButton('edit') && isset($transaction->parent_transaction_id))
                                <a class="btn btn-xs btn-default"
                                   href="{{ $transactionPresenter->getParentEditUrl() }}">
                                    <i class="fa fa-level-up"></i>
                                </a>
                            @endif

                            @if ($options->hasButton('edit'))
                                <a class="btn btn-xs btn-primary" href="{{ $transactionPresenter->getEditUrl() }}">
                                    <i class="fa fa-cog"></i>
                                </a>
                            @endif
                        </td>
                    @endif
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
@endif