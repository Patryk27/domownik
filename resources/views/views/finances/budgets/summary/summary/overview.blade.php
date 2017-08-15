@php
    /**
     * @var \App\ValueObjects\Budget\Summary $summary
     */
@endphp

<div class="panel panel-default">
    <div class="panel-heading">
        <div class="panel-title">
            <i class="fa fa-database"></i>&nbsp;
            {{ __('views/finances/budgets/summary.overview.title', [
                'year' => sprintf('%04d', $summary->getYear()),
                'month' => sprintf('%02d', $summary->getMonth()),
            ]) }}
        </div>
    </div>

    <div class="panel-body">
        <table class="table table-hover table-striped">
            <tbody>
            {{-- Estimated income --}}
            <tr>
                <th>
                    {{ __('views/finances/budgets/summary.overview.estimated-income') }}
                </th>

                <td>
                    @include('components.estimated-cost', ['cost' => $summary->getEstimatedIncome()])
                </td>
            </tr>

            {{-- Estimated expense --}}
            <tr>
                <th>
                    {{ __('views/finances/budgets/summary.overview.estimated-expense') }}
                </th>

                <td>
                    @include('components.estimated-cost', ['cost' => $summary->getEstimatedExpense()])
                </td>
            </tr>

            {{-- Estimated profit --}}
            <tr>
                <th>
                    {{ __('views/finances/budgets/summary.overview.estimated-profit') }}
                </th>

                <td>
                    @include('components.estimated-cost', ['cost' => $summary->getEstimatedProfit()])
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>