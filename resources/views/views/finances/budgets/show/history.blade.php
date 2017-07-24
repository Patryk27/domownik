{{-- Budget history --}}
<div class="panel panel-default">
    <div class="panel-heading">
        <div class="panel-title">
            <i class="fa fa-database"></i>&nbsp;
            {{ __('views/finances/budget/show.history.header') }}

            <div style="display:inline-block">
                {!!
                Form::select()
                    ->setIdAndName('budget-history-group-mode')
                    ->setItems(function() {
                        $items = [
                            \App\Services\Transaction\HistoryCollectorContract::GROUP_MODE_DAILY,
                            \App\Services\Transaction\HistoryCollectorContract::GROUP_MODE_WEEKLY,
                            \App\Services\Transaction\HistoryCollectorContract::GROUP_MODE_MONTHLY,
                            \App\Services\Transaction\HistoryCollectorContract::GROUP_MODE_YEARLY,
                        ];

                        $result = [];

                        foreach ($items as $item) {
                            $result[$item] = __(sprintf('views/finances/budget/show.history-group-mode.%s', $item));
                        }

                        return $result;
                    })
                !!}
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