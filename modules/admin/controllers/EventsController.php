<?php

namespace admin\controllers;

/**
 * EventsController implements the CRUD actions for Events model.
 */
class EventsController extends \admin\components\AdminController
{
	public $modelClass = 'app\models\Events';
	public $searchModelClass = 'app\models\search\EventsSearch';

	public $sectionTitle = 'Events';

}
