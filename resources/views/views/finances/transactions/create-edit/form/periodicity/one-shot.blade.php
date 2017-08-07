<div id="transaction-periodicity-one-shot"
     class="transaction-periodicity"
     data-transaction-periodicity-type="{{ \App\Models\Transaction::PERIODICITY_TYPE_ONE_SHOT }}">
    <hr>

    <h5>
        {{ __('views/finances/transactions/create-edit.transaction-periodicity.one-shot.title') }}
    </h5>

    <div class="periodicity-wrapper">
        {{-- Calendar is provided by the JS --}}
    </div>
</div>