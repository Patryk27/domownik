@php
    /**
     * @var \App\Modules\Finances\Models\Transaction $transaction
     */
@endphp

@extends('layouts/auth')

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

        {{ csrf_field() }}

        @yield('transaction-create-edit-form-partial')

        <ul class="nav nav-tabs" role="tablist">
            {{-- Basic transaction data --}}
            <li role="presentation" class="active">
                <a href="#tab-basic" aria-controls="tab-basic" role="tab" data-toggle="tab">
                    <i class="fa fa-cube"></i>&nbsp;
                    {{ __('Finances::views/transaction/create-edit.tabs.basic.title') }}
                </a>
            </li>

            {{-- Transaction value --}}
            <li role="presentation">
                <a href="#tab-value" aria-controls="tab-value" role="tab" data-toggle="tab">
                    <i class="fa fa-money"></i>&nbsp;
                    {{ __('Finances::views/transaction/create-edit.tabs.value.title') }}
                </a>
            </li>

            {{-- Transaction periodicity --}}
            <li role="presentation">
                <a href="#tab-periodicity" aria-controls="tab-periodicity" role="tab" data-toggle="tab">
                    <i class="fa fa-calendar"></i>&nbsp;
                    {{ __('Finances::views/transaction/create-edit.tabs.periodicity.title') }}
                </a>
            </li>
        </ul>

        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="tab-basic">
                @include('Finances::transaction.create-edit.tabs.basic')
            </div>

            <div role="tabpanel" class="tab-pane" id="tab-value">
                @include('Finances::transaction.create-edit.tabs.value')
            </div>

            <div role="tabpanel" class="tab-pane" id="tab-periodicity">
                @include('Finances::transaction.create-edit.tabs.periodicity')
            </div>
        </div>

        <hr>

        <div>
            <button type="submit" class="btn btn-success">
                <i class="fa fa-save"></i>&nbsp;
                {{ __('Finances::views/transaction/create-edit.save') }}
            </button>

            @isset($transaction)
                <div class="pull-right">
                    <button id="btnDeleteTransaction"
                            type="button"
                            class="btn btn-warning"
                            data-transaction-id="{{ $transaction->id }}">
                        <i class="fa fa-trash-o"></i>&nbsp;
                        {{ __('Finances::views/transaction/create-edit.delete') }}
                    </button>
                </div>
            @endisset

            {!! Form::requiredFields() !!}
        </div>

    </form>
@endsection