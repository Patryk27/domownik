{{-- Budget history --}}
<div class="panel panel-default">
    <div class="panel-heading">
        <div class="panel-title">
            <i class="fa fa-database"></i>&nbsp;
            {{ __('views/finances/budgets/show.history.header') }}

            <div style="display:inline-block">
                @php
                    $itemKeys = array_keys((array)__('views/finances/budgets/show.history-group-mode'));
                @endphp

                {!! Form::select(null, map_translation($itemKeys, 'views/finances/budgets/show.history-group-mode.%s'), null, [
                    'id' => 'budget-history-group-mode',
                    'class' => 'form-control',
                ]) !!}
            </div>
        </div>
    </div>

    <div class="panel-body">
        <div id="budget-history">
            @include('common.ajax.loader', [
                'icon' => true,
                'label' => true,
            ])
        </div>
    </div>
</div>