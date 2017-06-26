@php
    /**
      * @var \App\Modules\Finances\ValueObjects\ScheduledTransaction[] $transactions
      * @var string[] $transactionButtons
      */
@endphp

<table class="table table-hover table-striped transaction-list compact">
    <thead>
    <tr>
        <th>{{ __('Finances::common/transaction-list.table.transaction-date') }}</th>
        <th>{{ __('Finances::common/transaction-list.table.transaction-name') }}</th>
        <th>{{ __('Finances::common/transaction-list.table.transaction-amount') }}</th>

        @if(isset($transactionButtons))
            <th></th>
        @endif
    </tr>
    </thead>
    <tbody>
    @foreach ($transactions as $item)
        @php
            /**
              * @var \App\Modules\Finances\Models\Transaction|\App\Modules\Finances\ValueObjects\ScheduledTransaction $transaction
              */

            if ($item instanceof \App\Modules\Finances\ValueObjects\ScheduledTransaction) {
                $transaction = $item->getTransaction();
                $date = $item->getDate();
            } else {
                $transaction = $item;
                $date = $item->periodicity->date;
            }

            $transactionPresenter = $transaction->getPresenter();
        @endphp
        <tr>
            <td>{{ Date::format('%Y-%m-%e %a', $date) }}</td>
            <td>{{ $transaction->name }}</td>
            <td>
                @include('Finances::common.transaction.value', [
                    'transaction' => $transaction,
                ])
            </td>
            @if(isset($transactionButtons))
                <td class="transaction-list-buttons">
                    @if (in_array('edit-parent', $transactionButtons) && isset($transaction->parent_transaction_id))
                        <a class="btn btn-xs btn-default" href="{{ $transactionPresenter->getParentEditUrl() }}">
                            <i class="fa fa-level-up"></i>
                        </a>
                    @endif

                    @if (in_array('edit', $transactionButtons))
                        <a class="btn btn-xs btn-info" href="{{ $transactionPresenter->getEditUrl() }}">
                            <i class="fa fa-cog"></i>
                        </a>
                    @endif
                </td>
            @endif
        </tr>
    @endforeach
    </tbody>
</table>