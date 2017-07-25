@php
    /**
     * @var float|null $transactionValueConstantValue
     */
@endphp

<div class="transaction-value-wrapper"
     data-transaction-value-type="{{ \App\Models\Transaction::VALUE_TYPE_CONSTANT }}"
     style="display:none">
    <div class="form-group">
        {!! Form::label('value_constant_value', __('views/finances/transactions/create-edit.transaction-value-constant.label')) !!}

        <div class="input-group">
            <span class="input-group-addon">
                <i class="fa fa-money"></i>&nbsp;
            </span>

            {!! Form::text('value_constant_value', $transactionValueConstantValue, [
                'class' => 'form-control',
                'placeholder' => __('views/finances/transactions/create-edit.transaction-value-constant.placeholder'),
            ]) !!}
        </div>
    </div>
</div>