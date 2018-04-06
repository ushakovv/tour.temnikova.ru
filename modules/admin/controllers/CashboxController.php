<?php

namespace admin\controllers;

/**
 * CashboxController implements the CRUD actions for Cashbox model.
 */
class CashboxController extends \admin\components\AdminController
{
	public $modelClass = 'app\models\Cashbox';
	public $searchModelClass = 'app\models\search\CashboxSearch';

	public $sectionTitle = 'Онлайн-кассы';

}
