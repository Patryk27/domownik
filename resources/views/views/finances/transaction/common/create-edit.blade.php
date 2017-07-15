@php
    /**
     * @var \App\Models\Transaction $transaction
     * @var \App\Models\Budget $budget
     */
@endphp

@extends('layouts.app.auth')

@push('scripts')
<script>
  var AppView = App.Views.Finances.Transaction.CreateEdit.initializeView();
</script>
@endpush

@section('content')
    <form role="form"
          id="transactionForm"
          data-toggle="validator"
          action="{{ route('finances.transaction.store') }}"
          method="post">

        @isset($budget)
            {!!
                Form::hiddenInput()
                    ->setName('transactionParentType')
                    ->setValue(\App\Models\Transaction::PARENT_TYPE_BUDGET)
            !!}

            {!!
                Form::hiddenInput()
                    ->setName('transactionParentId')
                    ->setValue($budget->id)
             !!}
        @endisset

        @isset($transaction)
            {!!
                Form::hiddenInput()
                    ->setIdAndName('transactionId')
                    ->setValue($transaction->id)
             !!}
        @endisset

        <ul class="nav nav-tabs" role="tablist">
            {{-- Basic transaction data --}}
            <li role="presentation" class="active">
                <a href="#tab-basic" aria-controls="tab-basic" role="tab" data-toggle="tab">
                    <i class="fa fa-cube"></i>&nbsp;
                    {{ __('views/finances/transaction/common/create-edit.tabs.basic.title') }}
                </a>
            </li>

            {{-- Transaction value --}}
            <li role="presentation">
                <a href="#tab-value" aria-controls="tab-value" role="tab" data-toggle="tab">
                    <i class="fa fa-money"></i>&nbsp;
                    {{ __('views/finances/transaction/common/create-edit.tabs.value.title') }}
                </a>
            </li>

            {{-- Transaction periodicity --}}
            <li role="presentation">
                <a href="#tab-periodicity" aria-controls="tab-periodicity" role="tab" data-toggle="tab">
                    <i class="fa fa-calendar"></i>&nbsp;
                    {{ __('views/finances/transaction/common/create-edit.tabs.periodicity.title') }}
                </a>
            </li>
        </ul>

        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="tab-basic">
                @include('views.finances.transaction.common.create-edit.basic')
            </div>

            <div role="tabpanel" class="tab-pane" id="tab-value">
                @include('views.finances.transaction.common.create-edit.value')
            </div>

            <div role="tabpanel" class="tab-pane" id="tab-periodicity">
                @include('views.finances.transaction.common.create-edit.periodicity')
            </div>
        </div>

        <hr>

        <div>
            @include('common.form.save-button')

            @isset($transaction)
                @include('common.form.delete-button', [
                    'route' => route('finances.transaction.delete', $transaction->id),
                    'confirmationMessage' => __('views/finances/transaction/common/create-edit.delete-confirmation-message'),
                ])
            @endisset

            @include('common.form.required-fields')
        </div>

    </form>
@endsection