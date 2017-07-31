@php
    /**
     * @var \App\Models\Transaction|null $transaction
     * @var \App\Models\Model $transactionParent
     */
@endphp

@push('scripts')
    <script>
      var AppView = App.Views.Finances.Transaction.CreateEdit.initializeView();
    </script>
@endpush

@php
    $form = [
        'route' => isset($transaction) ? ['finances.transactions.update', $transaction->id] : 'finances.transactions.store',
        'method' => isset($transaction) ? 'put' : 'post',
    ];
@endphp

{!! Form::model($transaction, [
    'route' => $form['route'],
    'method' => $form['method'],
    'id' => 'transaction_form',
    'data-toggle' => 'validator',
]) !!}

<div class="panel-body">
    {!! Form::hidden('parent_type', $transactionParentType) !!}
    {!! Form::hidden('parent_id', $transactionParent->id) !!}

    <ul class="nav nav-tabs" role="tablist">
        {{-- Basic transaction data --}}
        <li role="presentation" class="active">
            <a href="#tab-basic" aria-controls="tab-basic" role="tab" data-toggle="tab">
                <i class="fa fa-cube"></i>&nbsp;
                {{ __('views/finances/transactions/create-edit.tabs.basic.title') }}
            </a>
        </li>

        {{-- Transaction value --}}
        <li role="presentation">
            <a href="#tab-value" aria-controls="tab-value" role="tab" data-toggle="tab">
                <i class="fa fa-money"></i>&nbsp;
                {{ __('views/finances/transactions/create-edit.tabs.value.title') }}
            </a>
        </li>

        {{-- Transaction periodicity --}}
        <li role="presentation">
            <a href="#tab-periodicity" aria-controls="tab-periodicity" role="tab" data-toggle="tab">
                <i class="fa fa-calendar"></i>&nbsp;
                {{ __('views/finances/transactions/create-edit.tabs.periodicity.title') }}
            </a>
        </li>
    </ul>

    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="tab-basic">
            @include('views.finances.transactions.create-edit.form.basic')
        </div>

        <div role="tabpanel" class="tab-pane" id="tab-value">
            @include('views.finances.transactions.create-edit.form.value')
        </div>

        <div role="tabpanel" class="tab-pane" id="tab-periodicity">
            @include('views.finances.transactions.create-edit.form.periodicity')
        </div>
    </div>

    <hr>

    @include('components.form.required-fields')
</div>

<div class="panel-footer">
    @include('components.form.buttons.save')

    @isset($transaction)
        @include('components.form.buttons.delete', [
            'url' => route('finances.transactions.destroy', $transaction->id),
            'message' => __('views/finances/transactions/create-edit.delete-confirmation-message'),
        ])
    @endisset
</div>

{!! Form::close() !!}