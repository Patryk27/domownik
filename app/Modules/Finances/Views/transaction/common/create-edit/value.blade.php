@php
    /**
     * @var \App\Modules\Finances\Models\Transaction $transaction
     */
@endphp


@php
    $transactionValueTypes = \App\Modules\Finances\Models\Transaction::getValueTypes();

    $transactionValueConstantValue = null;
    $transactionValueRangeFrom = null;
    $transactionValueRangeTo = null;

    if (isset($transaction)) {
        switch ($transaction->value_type) {
            case \App\Modules\Finances\Models\Transaction::VALUE_TYPE_CONSTANT:
                $transactionValueConstantValue = $transaction->value->value;
                break;

            case \App\Modules\Finances\Models\Transaction::VALUE_TYPE_RANGE:
                $transactionValueRangeFrom = $transaction->value->value_from;
                $transactionValueRangeTo = $transaction->value->value_to;
                break;
        }
    }
@endphp

{{-- Transaction value type --}}
{!!
    Form::select()
        ->setIdAndName('transactionValueType')
        ->setLabel(__('Finances::views/transaction/common/create-edit.transaction-value-type.label'))
        ->setValueFromModel($transaction, 'value_type')
        ->setRequired(true)
        ->setItems(function() use ($transactionValueTypes) {
            $items = [];

            foreach ($transactionValueTypes as $transactionValueType) {
                $items[$transactionValueType] =  __('Finances::common/transaction.value-type.' . $transactionValueType);
            }

            return $items;
        })
 !!}

<div class="transaction-value-wrapper"
     data-transaction-value-type="{{ \App\Modules\Finances\Models\Transaction::VALUE_TYPE_CONSTANT }}"
     style="display:none">
    {!!
        Form::textInput()
            ->setIdAndName('transactionValueConstantValue')
            ->setLabel(__('Finances::views/transaction/common/create-edit.transaction-value-constant.label'))
            ->setPlaceholder(__('Finances::views/transaction/common/create-edit.transaction-value-constant.placeholder'))
            ->setLeftAddonIcon('fa fa-money')
            ->setValue($transactionValueConstantValue)
     !!}
</div>

<div class="transaction-value-wrapper"
     data-transaction-value-type="{{ \App\Modules\Finances\Models\Transaction::VALUE_TYPE_RANGE }}"
     style="display:none">
    <div class="row">
        <div class="col-sm-12 col-md-6">
            {!!
                Form::textInput()
                    ->setIdAndName('transactionValueRangeFrom')
                    ->setLabel(__('Finances::views/transaction/common/create-edit.transaction-value-range-from.label'))
                    ->setPlaceholder(__('Finances::views/transaction/common/create-edit.transaction-value-range-from.placeholder'))
                    ->setLeftAddonIcon('fa fa-money')
                    ->setValue($transactionValueRangeFrom)
             !!}
        </div>

        <div class="col-sm-12 col-md-6">
            {!!
                Form::textInput()
                    ->setIdAndName('transactionValueRangeTo')
                    ->setLabel(__('Finances::views/transaction/common/create-edit.transaction-value-range-to.label'))
                    ->setPlaceholder(__('Finances::views/transaction/common/create-edit.transaction-value-range-to.placeholder'))
                    ->setLeftAddonIcon('fa fa-money')
                    ->setValue($transactionValueRangeTo)
             !!}
        </div>
    </div>
</div>