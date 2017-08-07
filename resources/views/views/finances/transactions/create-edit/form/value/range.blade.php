@php
    /**
     * @var float|null $transactionValueRangeFrom
     * @var float|null $transactionValueRangeTo
     */
@endphp

<div class="transaction-value-wrapper"
     data-transaction-value-type="{{ \App\Models\Transaction::VALUE_TYPE_RANGE }}"
     style="display:block">
    <div class="row">
        {{-- Range from --}}
        <div class="col-sm-12 col-md-6">
            <div class="form-group">
                {{ Form::label('value_range_from', __('views/finances/transactions/create-edit.transaction-value-range-from.label')) }}

                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-money"></i>&nbsp;
                    </span>

                    {!! Form::text('value_range_from', $transactionValueRangeFrom, [
                        'class' => 'form-control',
                        'placeholder' => __('views/finances/transactions/create-edit.transaction-value-range-from.placeholder'),
                    ]) !!}
                </div>
            </div>
        </div>

        {{-- Range to --}}
        <div class="col-sm-12 col-md-6">
            <div class="form-group">
                {{ Form::label('value_range_to', __('views/finances/transactions/create-edit.transaction-value-range-to.label')) }}

                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-money"></i>&nbsp;
                    </span>

                    {!! Form::text('value_range_to', $transactionValueRangeTo, [
                        'class' => 'form-control',
                        'placeholder' => __('views/finances/transactions/create-edit.transaction-value-range-to.placeholder'),
                    ]) !!}
                </div>
            </div>
        </div>
    </div>
</div>