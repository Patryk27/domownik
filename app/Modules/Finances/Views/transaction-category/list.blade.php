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
    <div id="transactionCategoryTree">
        {{ __('Finances::views/transaction-category/list.waiting') }}
    </div>

    <hr>

    <div class="alert alert-info">
        <i class="fa fa-info-circle"></i><br>
        {!! __('Finances::views/transaction-category/list.help-text.1') !!}
    </div>

    <hr>

    <div>
        @include('common.form.save-button')
    </div>
@endsection