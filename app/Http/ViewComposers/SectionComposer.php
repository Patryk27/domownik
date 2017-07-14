<?php

namespace App\Http\ViewComposers;

use App\Services\Section\ManagerContract as SectionManagerContract;
use App\Services\Sidebar\ManagerContract as SidebarManagerContract;
use App\Support\Classes\Controller as ControllerHelper;
use App\ValueObjects\Sidebar;
use App\ValueObjects\Sidebar\Item as SidebarItem;
use Illuminate\Support\Arr;
use Illuminate\View\View;

class SectionComposer {

	/**
	 * @var SectionManagerContract
	 */
	protected $sectionManager;

	/**
	 * @var SidebarManagerContract
	 */
	protected $sidebarManager;

	/**
	 * @var ControllerHelper
	 */
	protected $controllerHelper;

	/**
	 * @var string
	 */
	protected $sectionNames;

	/**
	 * @var Sidebar[]
	 */
	protected $sidebars;

	/**
	 * @var Sidebar
	 */
	protected $nullSidebar;

	/**
	 * @param SectionManagerContract $sectionManager
	 * @param SidebarManagerContract $sidebarManager
	 * @param ControllerHelper $controllerHelper
	 */
	public function __construct(
		SectionManagerContract $sectionManager,
		SidebarManagerContract $sidebarManager,
		ControllerHelper $controllerHelper
	) {
		$this->sectionManager = $sectionManager;
		$this->sidebarManager = $sidebarManager;
		$this->controllerHelper = $controllerHelper;

		$this->sectionNames = $this->sectionManager->getSectionNames();
		$this->sidebars = $this->sidebarManager->getSidebars();

		$this->nullSidebar = new Sidebar('', new SidebarItem());
	}

	/**
	 * @param View $view
	 * @return void
	 */
	public function compose(View $view) {
		$sectionName = $this->controllerHelper->getSectionName();

		$view->with('sectionNames', $this->sectionNames);
		$view->with('sectionName', $sectionName);
		$view->with('sidebar', Arr::get($this->sidebars, $sectionName, $this->nullSidebar));
	}

}