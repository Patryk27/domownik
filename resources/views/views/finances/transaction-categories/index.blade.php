@php
    /**
     * @var array $form
     */
@endphp

@extends('layouts.app.auth')

@push('scripts')
    <script>
        var AppView = App.Views.Finances.TransactionCategories.Index.initializeView();
    </script>
@endpush

@section('content')
    {!! Form::open([
        'id' => 'editTransactionCategoriesForm',
        'url' => $form['url'],
        'method' => $form['method'],
    ]) !!}

    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-title">
                <i class="fa fa-tags"></i>&nbsp;
                {{ __('views/finances/transaction-categories/index.page.title') }}
            </div>
        </div>

        <div class="panel-body">
            <a id="btnCreateNewRootCategory"
               href="#"
               class="btn btn-primary">
                <i class="fa fa-plus"></i>&nbsp;
                {{ __('views/finances/transaction-categories/index.create-new-root-category') }}
            </a>

            <hr>

            <div id="transactionCategoryTree">
                {{ __('views/finances/transaction-categories/index.waiting') }}
            </div>
        </div>

        <div class="panel-footer">
            @include('components.form.buttons.save')
        </div>
    </div>

    {!! Form::close() !!}
@endsection