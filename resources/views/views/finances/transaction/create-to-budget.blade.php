@php
    /**
     * @var \App\Models\Budget $budget
     */
@endphp

@extends('layouts.app.auth')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-title">
                <i class="fa fa-plus-square"></i>&nbsp;
                {{ __('views/finances/transaction/create-to-budget.page.title', [
                    'budgetName' => $budget->name
                ]) }}
            </div>
        </div>

        <div class="panel-body">
            @include('views.finances.transaction.common.create-edit', [
                'budget' => $budget,
                'transaction' => null,
            ])
        </div>
    </div>
@endsection