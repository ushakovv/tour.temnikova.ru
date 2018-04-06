<?php

namespace admin\controllers;

/**
 * SitesController implements the CRUD actions for Sites model.
 */
class SitesController extends \admin\components\AdminController
{
	public $modelClass = 'app\models\Sites';
	public $searchModelClass = 'app\models\search\SitesSearch';

	public $sectionTitle = 'Сайты';

}
