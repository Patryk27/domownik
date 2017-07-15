@extends('layouts.app.auth')

@push('scripts')
<script>
  var AppView = App.Views.Finances.Budget.Create.initializeView();
</script>
@endpush

@section('title')
    {{ __('views/finances/budget/create.page.title') }}
@endsection

@section('content')
    <form role="form"
          id="budgetForm"
          data-toggle="validator"
          action="{{ route('finances.budget.store') }}"
          method="post">

        {{-- Budget name --}}
        {!!
            Form::textInput()
                ->setIdAndName('budgetName')
                ->setLabel(__('views/finances/budget/create.budget-name.label'))
                ->setPlaceholder(__('views/finances/budget/create.budget-name.placeholder'))
                ->setAutoValue(true)
                ->setRequired(true)
                ->setAutofocus(true)
         !!}

        {{-- Budget description --}}
        {!!
            Form::textArea()
                ->setIdAndName('budgetDescription')
                ->setLabel(__('views/finances/budget/create.budget-description.label'))
                ->setPlaceholder(__('views/finances/budget/create.budget-description.placeholder'))
                ->setAutoValue(true)
                ->setRequired(false)
         !!}

        {{-- Budget type --}}
        {!!
            Form::select()
                ->setIdAndName('budgetType')
                ->setLabel(__('views/finances/budget/create.budget-type.label'))
                ->setAutoValue(true)
                ->setRequired(true)
                ->setItems(function() use ($budgetTypes) {
                    $items = [];

                    foreach ($budgetTypes as $budgetType) {
                        $items[$budgetType] =  __('common/budget.type.' . $budgetType);
                    }

                    return $items;
                })
         !!}

        {{-- Consolidated budgets, if required --}}
        <div id="consolidatedBudgetsWrapper" style="display:none">
            {!!
                Form::select()
                    ->setIdAndName('consolidatedBudgets')
                    ->setLabel(__('views/finances/budget/create.consolidated-budgets.label'))
                    ->setAutoValue(true)
                    ->setMultiple(true)
                    ->setItems(function() use ($activeBudgets) {
                        $items = [];

                        foreach ($activeBudgets as $activeBudget) {
                            $items[] = $activeBudget->name;
                        }

                        return $items;
                    })
             !!}
        </div>

        <div>
            @include('common.form.save-button')
            @include('common.form.required-fields')
        </div>
    </form>
@endsection