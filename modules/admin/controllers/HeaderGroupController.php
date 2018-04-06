<?php

namespace admin\controllers;

/**
 * HeaderGroupController implements the CRUD actions for HeaderGroup model.
 */
class HeaderGroupController extends \admin\components\AdminController
{
	public $modelClass = 'app\models\HeaderGroup';
	public $searchModelClass = 'app\models\search\HeaderGroupSearch';

	public $sectionTitle = 'Header группы';

}
