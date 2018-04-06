<?php

namespace admin\controllers;

/**
 * CityController implements the CRUD actions for City model.
 */
class CityController extends \admin\components\AdminController
{
	public $modelClass = 'app\models\City';
	public $searchModelClass = 'app\models\search\CitySearch';

	public $sectionTitle = 'Города';

}
