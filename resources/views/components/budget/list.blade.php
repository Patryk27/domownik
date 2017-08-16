@php
    /**
     * @var \Illuminate\Support\Collection|\App\Models\Budget[] $budgets
     */
@endphp

<p>
    {!! Lang::choice(__('models/budget.misc.found-count', [
        'count' => $budgets->count(),
    ]), $budgets->count()) !!}
</p>

<table class="table table-hover table-striped">
    <thead>
    <tr>
        <th>{{ __('models/budget.fields.id') }}</th>
        <th>{{ __('models/budget.fields.name') }}</th>
        <th>{{ __('models/budget.fields.type') }}</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    @foreach ($budgets as $budget)
        @php
            $budgetPresenter = $budget->getPresenter();
        @endphp
        <tr>
            <td>{{ $budget->id }}</td>
            <td>{{ $budget->name }}</td>
            <td>{{ strtolower(\App\Models\Budget::getTypesSelect()[$budget->type]) }}</td>
            <td>
                {{-- Show budget --}}
                <a class="btn btn-xs btn-default"
                   href="{{ $budgetPresenter->getShowUrl() }}">
                    <i class="fa fa-dashboard"></i>&nbsp;
                    {{ __('components/budget/list.show') }}
                </a>

                {{-- Edit budget --}}
                <a class="btn btn-xs btn-primary"
                   href="{{ $budgetPresenter->getEditUrl() }}">
                    <i class="fa fa-gear"></i>&nbsp;
                    {{ __('components/budget/list.edit') }}
                </a>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>