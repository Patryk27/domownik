@php
    /**
     * @var array $form
     * @var \App\Models\Budget|null $budget
     * @var \Illuminate\Support\Collection|string[] $budgetsSelect
     */
@endphp

@push('scripts')
    <script>
        var AppView = App.Views.Finances.Budgets.CreateEdit.initializeView();
    </script>
@endpush

{!! Form::model($budget, [
    'url' => $form['url'],
    'method' => $form['method'],
    'id' => 'budgetForm',
    'class' => 'form-ajax',
]) !!}

<div class="panel-body">
    {{-- Budget name --}}
    <div class="form-group required">
        {!! Form::label('name', __('views/finances/budgets/create-edit/form.name.label')) !!}
        {!! Form::text('name', null, [
            'class' => 'form-control',
            'placeholder' => __('views/finances/budgets/create-edit/form.name.placeholder'),
            'required',
            'autofocus',
        ]) !!}
    </div>

    {{-- Budget description --}}
    <div class="form-group">
        {!! Form::label('description', __('views/finances/budgets/create-edit/form.description.label')) !!}
        {!! Form::textarea('description', null, [
            'class' => 'form-control',
            'placeholder' => __('views/finances/budgets/create-edit/form.description.placeholder'),
        ]) !!}
    </div>

    {{-- Budget type --}}
    <div class="form-group required">
        {!! Form::label('type', __('views/finances/budgets/create-edit/form.type.label')) !!}
        {!! Form::select('type', \App\Models\Budget::getTypesSelect(), null, [
            'class' => 'form-control',
            'disabled' => isset($budget),
            'required' => is_null($budget),
        ]) !!}
    </div>

    {{-- Consolidated budgets, if required --}}
    <div id="consolidated_budgets_wrapper" style="display:none">
        <div class="form-group">
            {!! Form::label('consolidated_budgets', __('views/finances/budgets/create-edit/form.consolidated-budgets.label')) !!}
            {!! Form::select('consolidated_budgets', $budgetsSelect, null, [
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

    @isset($budget)
        @include('components.form.buttons.delete', [
            'url' => route('finances.budgets.destroy', $budget->id),
            'message' => __('requests/budget/crud.prompts.delete'),
        ])
    @endisset
</div>

{!! Form::close() !!}