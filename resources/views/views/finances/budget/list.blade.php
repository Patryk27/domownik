@php
    /**
     * @var \App\Models\Budget[] $budgets
     */
@endphp

@extends('layouts.app.auth')

@section('content')
    {{-- Budgets management --}}
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-title">
                <i class="fa fa-cogs"></i>&nbsp;
                {{ __('views/finances/budget/list.budget-management') }}
            </div>
        </div>

        <div class="panel-body">
            <a href="{{ route('finances.budget.create') }}"
               class="btn btn-success">
                <i class="fa fa-plus"></i>&nbsp;
                {{ __('views/finances/budget/list.create-budget') }}
            </a>
        </div>
    </div>

    {{-- Budgets list --}}
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-title">
                <i class="fa fa-list"></i>&nbsp;
                {{ __('views/finances/budget/list.budget-list') }}
            </div>
        </div>

        <div class="panel-body">
            <p>
                @php($budgetCount = count($budgets))

                {!! Lang::choice(__('common/budget.misc.found-count', [
                    'count' => $budgetCount,
                ]), $budgetCount) !!}
            </p>

            <table class="table table-hover table-striped">
                <thead>
                <tr>
                    <th>{{ __('components/budget-list.budget-id') }}</th>
                    <th>{{ __('components/budget-list.budget-name') }}</th>
                    <th>{{ __('components/budget-list.budget-type') }}</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach ($budgets as $budget)
                    @php
                        /**
                         * @var \App\Models\Budget $budget
                         */

                        $budgetPresenter = $budget->getPresenter();
                    @endphp
                    <tr>
                        <td>{{ $budget->id }}</td>
                        <td>{{ $budget->name }}</td>
                        <td>{{ strtolower(__(sprintf('common/budget.type.%s', $budget->type))) }}</td>
                        <td>
                            <a class="btn btn-xs btn-primary"
                               href="{{ $budgetPresenter->getShowUrl() }}">
                                <i class="fa fa-gear"></i>&nbsp;
                                {{ __('views/finances/budget/list.budgets-table.body.btn-show') }}
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection