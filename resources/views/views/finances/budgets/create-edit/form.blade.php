@php
    /**
     * @var \App\Models\Budget|null $budget
     * @var array $availableConsolidatedBudgets
     */
@endphp

@push('scripts')
    <script>
      var AppView = App.Views.Finances.Budget.CreateEdit.initializeView();
    </script>
@endpush

{!! Form::model($budget, [
    'route' => 'finances.budgets.store',
    'method' => 'post',
    'id' => 'budgetForm',
    'class' => 'form-ajax',
]) !!}

<div class="panel-body">
    {{-- Budget name --}}
    <div class="form-group required">
        {!! Form::label('name', __('views/finances/budgets/create.name.label')) !!}
        {!! Form::text('name', null, [
            'class' => 'form-control',
            'placeholder' => __('views/finances/budgets/create.name.placeholder'),
            'required',
            'autofocus',
        ]) !!}
    </div>

    {{-- Budget description --}}
    <div class="form-group">
        {!! Form::label('description', __('views/finances/budgets/create.description.label')) !!}
        {!! Form::textarea('description', null, [
            'class' => 'form-control',
            'placeholder' => __('views/finances/budgets/create.description.placeholder'),
        ]) !!}
    </div>

    {{-- Budget type --}}
    <div class="form-group required">
        {!! Form::label('type', __('views/finances/budgets/create.type.label')) !!}
        {!! Form::select('type', \App\Models\Budget::getTypesSelect(), null, [
            'class' => 'form-control',
            'required',
        ]) !!}
    </div>

    {{-- Consolidated budgets, if required --}}
    <div id="consolidated_budgets_wrapper" style="display:none">
        <div class="form-group">
            {!! Form::label('consolidated_budgets', __('views/finances/budgets/create.consolidated-budgets.label')) !!}
            {!! Form::select('consolidated_budgets', $availableConsolidatedBudgets, null, [
                'class' => 'form-control',
                'multiple',
            ]) !!}
        </div>
    </div>

    <hr>

    @include('components.form.required-fields')
</div>

<div class="panel-footer">
    @include('components.form.buttons.save')
</div>

{!! Form::close() !!}