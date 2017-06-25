<?php

namespace App\Modules\Finances\Module;

use App\Modules\Scaffolding\Module\Director as AbstractDirector;
use App\Modules\ScaffoldingContract\Module\Director as DirectorContract;
use App\Modules\Finances\Repositories\Contracts\BudgetRepositoryContract;

class Director
	extends AbstractDirector {

	/**
	 * @var BudgetRepositoryContract
	 */
	protected $budgetRepository;

	/**
	 * @param BudgetRepositoryContract $budgetRepository
	 */
	public function __construct(
		BudgetRepositoryContract $budgetRepository
	) {
		$this->budgetRepository = $budgetRepository;
	}

	/**
	 * @inheritdoc
	 */
	public function initialize(): DirectorContract {
		parent::initialize();

		$this->includeActiveBudgetsInSidebar();

		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function getName(): string {
		return 'Finances';
	}

	/**
	 * @return $this
	 * @throws \App\Exceptions\Exception
	 */
	protected function includeActiveBudgetsInSidebar(): self {
		$budgets = $this->budgetRepository->getActiveBudgets();

		$item = $this->sidebar->getItemByName('budget.list');

		foreach ($budgets as $budget) {
			// @todo show folder-opened icon for currently selected budget (can be deduced from the breadcrumbs)
			$itemTemplate = $this->sidebar->getItemByName('budget.list.item-template');

			$subitem = $itemTemplate->getClone();
			$subitem
				->setName(sprintf('budget-%d', $budget->id))
				->setUrl(sprintf('/finances/budget/show/%d', $budget->id))
				->setCaption($budget->name);

			$item->addSubitem($subitem);
		}

		return $this;
	}

}
