@php
    /**
     * @var array $form
     * @var \Illuminate\Support\Collection|string[] $budgetsSelect
     */
@endphp

@extends('layouts.app.auth')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-title">
                <i class="fa fa-money"></i>&nbsp;
                {{ __('views/finances/budgets/create.page.title') }}
            </div>
        </div>

        @include('views.finances.budgets.create-edit.form', [
            'form' => $form,
            'budget' => null,
            'budgetsSelect' => $budgetsSelect,
        ])
    </div>
@endsection