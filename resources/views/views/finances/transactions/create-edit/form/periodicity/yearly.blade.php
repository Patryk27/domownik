<div id="transaction-periodicity-yearly"
     class="transaction-periodicity"
     data-transaction-periodicity-type="{{ \App\Models\Transaction::PERIODICITY_TYPE_YEARLY }}">
    <hr>

    <h5>
        {{ __('views/finances/transactions/create-edit.transaction-periodicity.yearly.title') }}
    </h5>

    <div class="periodicity-wrapper">
        {{-- Calendar is provided by the JS --}}
    </div>
</div>