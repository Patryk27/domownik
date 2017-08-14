@php
    /**
     * @var \App\ValueObjects\Budget\Summary $summary
     */
@endphp

<table class="table table-hover table-striped">
    <tbody>

    {{-- Estimated income --}}
    <tr>
        <th>
            {{ __('views/finances/budgets/summary.summary.estimated-income') }}
        </th>

        <td>
            @include('components.estimated-cost', ['cost' => $summary->getEstimatedIncome()])
        </td>

        <td>
            <a class="btn btn-xs btn-default" href="{{-- @todo put link after creating transaction search --}}">
                {{ __('views/finances/budgets/summary.summary.see-transactions') }}
            </a>
        </td>
    </tr>

    {{-- Estimated expense --}}
    <tr>
        <th>
            {{ __('views/finances/budgets/summary.summary.estimated-expense') }}
        </th>

        <td>
            @include('components.estimated-cost', ['cost' => $summary->getEstimatedExpense()])
        </td>

        <td>
            <a class="btn btn-xs btn-default" href="{{-- @todo put link after creating transaction search --}}">
                {{ __('views/finances/budgets/summary.summary.see-transactions') }}
            </a>
        </td>
    </tr>

    {{-- Estimated profit --}}
    <tr>
        <th>
            {{ __('views/finances/budgets/summary.summary.estimated-profit') }}
        </th>

        <td>
            @include('components.estimated-cost', ['cost' => $summary->getEstimatedProfit()])
        </td>

        <td>
            &nbsp;
        </td>
    </tr>
    </tbody>
</table>