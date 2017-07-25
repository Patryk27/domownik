<div id="transaction-periodicity-weekly"
     class="transaction-periodicity"
     data-transaction-periodicity-type="{{ \App\Models\Transaction::PERIODICITY_TYPE_WEEKLY }}">
    <hr>

    <h5>
        {{ __('views/finances/transactions/create-edit.transaction-periodicity.weekly.title') }}
    </h5>

    <div class="form-group">
        <div class="periodicity-wrapper">
            @foreach (Calendar::getWeekdaysCapitalized() as $weekDayNumber => $weekDay)
                <div class="checkbox">
                    <label>
                        {!! Form::checkbox('periodicty_weekly_days[]', $weekDay) !!}
                        {{ __('calendar.week-days.' . $weekDay) }}
                    </label>
                </div>
            @endforeach
        </div>
    </div>
</div>