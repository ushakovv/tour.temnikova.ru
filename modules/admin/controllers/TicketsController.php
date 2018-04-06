<?php

namespace admin\controllers;

/**
 * TicketsController implements the CRUD actions for Tickets model.
 */
class TicketsController extends \admin\components\AdminController
{
	public $modelClass = 'app\models\Tickets';
	public $searchModelClass = 'app\models\search\TicketsSearch';

	public $sectionTitle = 'Билеты';

}
