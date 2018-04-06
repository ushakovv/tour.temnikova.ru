<?php

namespace admin\controllers;

/**
 * PartnerController implements the CRUD actions for Partner model.
 */
class PartnerController extends  \admin\components\AdminController
{
	public $modelClass = 'app\models\Partner';
	public $searchModelClass = 'app\models\search\PartnerSearch';

	public $sectionTitle = 'Партнеры';

}
