@php
    /**
     * @var array $recentTransactionsChart
     */
@endphp

<div class="panel panel-default">
    <div class="panel-heading">
        <div class="panel-title">
            <i class="fa fa-database"></i>&nbsp;
            {{ __('views/finances/budgets/show.history.header') }}

            @if (!empty($recentTransactionsChart))
                <div class="inline-block">
                    {!! Form::select(null, __('views/finances/budgets/show.history.group-modes'), null, [
                        'id' => 'budget-history-group-mode',
                        'class' => 'form-control',
                    ]) !!}
                </div>
            @endif
        </div>
    </div>

    <div class="panel-body">
        <div id="budget-history">
            @if (empty($recentTransactionsChart))
                <p class="no-data">
                    {{ __('components/table.no-data') }}
                </p>
            @else
                @include('components.ajax.loader', [
                    'icon' => true,
                    'label' => true,
                ])
            @endif
        </div>
    </div>
</div>