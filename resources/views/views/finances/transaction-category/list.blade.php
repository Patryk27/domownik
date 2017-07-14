@extends('layouts.application.auth')

@push('scripts')
<script>
  var AppView = App.Views.Finances.TransactionCategory.List.initializeView();
</script>
@endpush

@section('title')
    {{ __('Finances::views/transaction-category/list.page.title') }}
@endsection

@section('content')
    <form id="editTransactionCategoriesForm"
          action="{{ route('finances.transaction-category.store') }}"
          method="post">
        <a id="btnCreateNewRootCategory"
           href="#"
           class="btn btn-xs btn-primary">
            <i class="fa fa-plus"></i>&nbsp;
            {{ __('Finances::views/transaction-category/list.create-new-root-category') }}
        </a>

        <hr>

        <div id="transactionCategoryTree">
            {{ __('Finances::views/transaction-category/list.waiting') }}
        </div>

        <hr>

        <div>
            @include('common.form.save-button')
        </div>
    </form>
@endsection