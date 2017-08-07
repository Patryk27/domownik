@php
    /**
     * @var array $form
     * @var \App\Models\Budget $budget
     * @var \Illuminate\Support\Collection|string[] $budgetsSelect
     */
@endphp

@extends('layouts.app.auth')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-title">
                <i class="fa fa-money"></i>&nbsp;
                {{ __('views/finances/budgets/edit.page.title', [
                    'budgetName' => $budget->name,
                ]) }}
            </div>
        </div>

        @include('views.finances.budgets.create-edit.form', [
            'form' => $form,
            'budget' => $budget,
            'budgetsSelect' => $budgetsSelect,
        ])
    </div>
@endsection