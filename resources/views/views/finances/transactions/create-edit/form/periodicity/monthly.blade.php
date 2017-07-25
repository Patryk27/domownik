<div id="transaction-periodicity-monthly"
     class="transaction-periodicity"
     data-transaction-periodicity-type="{{ \App\Models\Transaction::PERIODICITY_TYPE_MONTHLY }}">
    <hr>

    <h5>
        {{ __('views/finances/transactions/create-edit.transaction-periodicity.monthly.title') }}
    </h5>

    <div class="form-group" data-control-name="transactionPeriodicityMonthlyDays">
        <div class="periodicity-wrapper">
            <div class="row">
                @for($week = 1; $week <= 5; ++$week)
                    <div class="col-sm-6 col-md-2">
                        @for($day = 1; $day <= 7; ++$day)
                            @php
                                $dayNumber = ($week - 1) * 7 + $day
                            @endphp

                            @if ($dayNumber <= 31)
                                <div class="checkbox">
                                    <label>
                                        {!! Form::checkbox('periodicity_monthly_days[]', $dayNumber) !!}
                                        {{ $dayNumber }}
                                    </label>
                                </div>
                            @endif
                        @endfor
                    </div>
                @endfor
            </div>
        </div>

        <div class="help-block"></div>
    </div>

    <h5 class="text-muted">
        {{ __('views/finances/transactions/create-edit.transaction-periodicity.monthly.warning') }}
    </h5>
</div>