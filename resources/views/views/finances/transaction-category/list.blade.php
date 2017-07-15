@extends('layouts.app.auth')

@push('scripts')
<script>
  var AppView = App.Views.Finances.TransactionCategory.List.initializeView();
</script>
@endpush

@section('content')
    <form id="editTransactionCategoriesForm"
          action="{{ route('finances.transaction-category.store') }}"
          method="post">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="panel-title">
                    <i class="fa fa-tags"></i>&nbsp;
                    {{ __('views/finances/transaction-category/list.page.title') }}
                </div>
            </div>

            <div class="panel-body">
                <a id="btnCreateNewRootCategory"
                   href="#"
                   class="btn btn-primary">
                    <i class="fa fa-plus"></i>&nbsp;
                    {{ __('views/finances/transaction-category/list.create-new-root-category') }}
                </a>

                <hr>

                <div id="transactionCategoryTree">
                    {{ __('views/finances/transaction-category/list.waiting') }}
                </div>
            </div>

            <div class="panel-footer">
                @include('common.form.save-button')
            </div>
        </div>
    </form>
@endsection