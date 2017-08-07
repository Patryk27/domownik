@php
    /**
     * @var \App\Models\Transaction $transaction
     * @var \App\Models\Model $transactionParent
     * @var string $transactionParentType
     * @var \App\Models\TransactionCategory[] $categories
     */
@endphp

{{-- Transaction name --}}
<div class="form-group required">
    {!! Form::label('name', __('views/finances/transactions/create-edit.transaction-name.label')) !!}
    {!! Form::text('name', null, [
        'class' => 'form-control',
        'placeholder' => __('views/finances/transactions/create-edit.transaction-name.placeholder'),
        'required',
        'autofocus',
    ]) !!}
</div>

<div class="row">
    {{-- Transaction type --}}
    <div class="col-xs-12 col-sm-12 col-md-6">
        <div class="form-group required">
            {!! Form::label('type', __('views/finances/transactions/create-edit.transaction-type.label')) !!}
            {!! Form::select('type', \App\Models\Transaction::getTypesSelect(), null, [
                'class' => 'form-control',
                'required',
            ]) !!}
        </div>
    </div>

    {{-- Transaction category --}}
    <div class="col-xs-12 col-sm-12 col-md-6">
        <div class="form-group">
            @php
                $categories = [
                    null => __('views/finances/transactions/create-edit.transaction-category.empty-option')
                ] + $categories;
            @endphp

            {!! Form::label('category_id', __('views/finances/transactions/create-edit.transaction-category.label')) !!}
            {!! Form::select('category_id', $categories, null, [
                'class' => 'form-control',
            ]) !!}
        </div>
    </div>
</div>

{{-- Transaction description --}}
<div class="form-group">
    {!! Form::label('description', __('views/finances/transactions/create-edit.transaction-description.label')) !!}
    {!! Form::textarea('description', null, [
        'placeholder' => __('views/finances/transactions/create-edit.transaction-description.placeholder'),
        'class' => 'form-control',
    ]) !!}
</div>