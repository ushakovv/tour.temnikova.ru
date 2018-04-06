<?php

namespace admin\controllers;

/**
 * FooterGroupController implements the CRUD actions for FooterGroup model.
 */
class FooterGroupController extends \admin\components\AdminController
{
	public $modelClass = 'app\models\FooterGroup';
	public $searchModelClass = 'app\models\search\FooterGroupSearch';

	public $sectionTitle = 'Футер';

}
