@php
    /**
     * @var \App\Models\Transaction $transaction
     */
@endphp


@php
    $transactionValueTypes = \App\Models\Transaction::getValueTypes();

    $transactionValueConstantValue = null;
    $transactionValueRangeFrom = null;
    $transactionValueRangeTo = null;

    if (isset($transaction)) {
        switch ($transaction->value_type) {
            case \App\Models\Transaction::VALUE_TYPE_CONSTANT:
                $transactionValueConstantValue = $transaction->value->value;
                break;

            case \App\Models\Transaction::VALUE_TYPE_RANGE:
                $transactionValueRangeFrom = $transaction->value->value_from;
                $transactionValueRangeTo = $transaction->value->value_to;
                break;
        }
    }
@endphp

{{-- Transaction value type --}}
<div class="form-group required">
    {!! Form::label('value_type', __('views/finances/transactions/create-edit.transaction-value-type.label')) !!}
    {!! Form::select('value_type', \App\Models\Transaction::getValueTypesSelect(), null, [
        'class' => 'form-control',
        'required',
    ]) !!}
</div>

@include('views.finances.transactions.create-edit.form.value.constant')
@include('views.finances.transactions.create-edit.form.value.range')