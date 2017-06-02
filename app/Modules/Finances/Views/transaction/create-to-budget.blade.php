@extends('Finances::transaction.create-edit.page')

@section('title')
    {{ __('Finances::views/transaction/create-to-budget.page.title', [
        'budgetName' => $budget->name
    ]) }}
@endsection

@section('transaction-create-edit-form-partial')
    {!!
        Form::hiddenInput()
            ->setName('transactionParentType')
            ->setValue(\App\Modules\Finances\Models\Transaction::PARENT_TYPE_BUDGET)
    !!}

    {!!
        Form::hiddenInput()
            ->setName('transactionParentId')
            ->setValue($budget->id)
     !!}
@endsection