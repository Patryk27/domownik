@php
    /**
     * @var \App\ValueObjects\EstimatedCost $cost
     */
@endphp

<div class="estimated-cost {{ $cost->getEstimate() < 0 ? 'negative' : 'positive' }}">
    @if ($cost->getEstimateMin() === $cost->getEstimateMax())
        {{ Currency::formatWithUnit($cost->getEstimate()) }}
    @else
        {{ Currency::formatWithUnit($cost->getEstimateMin()) }}
        -
        {{ Currency::formatWithUnit($cost->getEstimateMax()) }}
    @endif
</div>