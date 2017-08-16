<?php

namespace App\Services\ValueObjects;

use App\ValueObjects\EstimatedCost;

class EstimatedCostBuilder {

	/**
	 * @var float
	 */
	protected $estimateMin;

	/**
	 * @var float
	 */
	protected $estimateMax;

	/**
	 * EstimatedCostBuilder constructor.
	 */
	public function __construct() {
		$this->estimateMin = 0;
		$this->estimateMax = 0;
	}

	/**
	 * @return float
	 */
	public function getEstimateMin(): float {
		return $this->estimateMin;
	}

	/**
	 * @param float $estimateMin
	 * @return $this
	 */
	public function setEstimateMin(float $estimateMin) {
		$this->estimateMin = $estimateMin;
		return $this;
	}

	/**
	 * @param float $value
	 * @return $this
	 */
	public function addEstimateMin(float $value) {
		$this->estimateMin += $value;
		return $this;
	}

	/**
	 * @return float
	 */
	public function getEstimateMax(): float {
		return $this->estimateMax;
	}

	/**
	 * @param float $estimateMax
	 * @return $this
	 */
	public function setEstimateMax(float $estimateMax) {
		$this->estimateMax = $estimateMax;
		return $this;
	}

	/**
	 * @param float $value
	 * @return $this
	 */
	public function addEstimateMax(float $value) {
		$this->estimateMax += $value;
		return $this;
	}

	/**
	 * @param EstimatedCost $cost
	 * @return $this
	 */
	public function addEstimateCost(EstimatedCost $cost) {
		$this->estimateMin += $cost->getEstimateMin();
		$this->estimateMax += $cost->getEstimateMax();
		return $this;
	}

	/**
	 * @return EstimatedCost
	 */
	public function build(): EstimatedCost {
		return new EstimatedCost($this->estimateMin, $this->estimateMax);
	}

}