@php
    /**
     * @var \App\Modules\Finances\Models\Transaction $transaction
     */
    $divClass = '';

    switch ($transaction->type) {
        case \App\Modules\Finances\Models\Transaction::TYPE_INCOME:
            $divClass = 'transaction-income';
            break;

        case \App\Modules\Finances\Models\Transaction::TYPE_EXPENSE:
            $divClass = 'transaction-expense';
            break;
    }
@endphp

@php
    $transactionValue = $transaction->value;
@endphp

<div class="{{ $divClass }}">
    @php
        $sign = $transaction->type === \App\Modules\Finances\Models\Transaction::TYPE_EXPENSE ? '-' : '';

        switch ($transaction->value_type) {
            case \App\Modules\Finances\Models\Transaction::VALUE_TYPE_CONSTANT:
                /**
                 * @var \App\Modules\Finances\Models\TransactionValueConstant $transactionValue
                 */
                echo $sign . Currency::formatWithUnit($transactionValue->value);
                break;

            case \App\Modules\Finances\Models\Transaction::VALUE_TYPE_RANGE:
                /**
                 * @var \App\Modules\Finances\Models\TransactionValueRange $transactionValue
                 */
                echo $sign . Currency::formatWithUnit($transactionValue->value_from);
                echo ' &mdash; ';
                echo $sign . Currency::formatWithUnit($transactionValue->value_to);
                break;

            default:
                echo '??';
        }
    @endphp
</div>