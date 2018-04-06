<?php

namespace admin\controllers;

/**
 * SocialGroupController implements the CRUD actions for SocialGroup model.
 */
class SocialGroupController extends \admin\components\AdminController
{
	public $modelClass = 'app\models\SocialGroup';
	public $searchModelClass = 'app\models\search\SocialGroupSearch';

	public $sectionTitle = 'Social Groups';

}
