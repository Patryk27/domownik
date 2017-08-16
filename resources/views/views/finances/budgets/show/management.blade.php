@php
    /**
     * @var \App\Models\Budget $budget
     */
@endphp

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-6">
        @include('views.finances.budgets.show.management.budget')
    </div>

    <div class="col-xs-12 col-sm-12 col-md-6">
        @include('views.finances.budgets.show.management.transactions')
    </div>
</div>