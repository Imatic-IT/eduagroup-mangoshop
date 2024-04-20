<?php declare(strict_types = 1);

namespace MangoShop\Bicistickers\Admin\Presenters;

use Nette\Application\Responses\TextResponse;
use Nette\Application\UI\Presenter;

class DashboardPresenter extends Presenter
{
	public function actionDefault()
	{
		$this->sendResponse(new TextResponse('dashboard'));
	}
}
