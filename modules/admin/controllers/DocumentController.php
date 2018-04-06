<?php

namespace admin\controllers;

/**
 * DocumentController implements the CRUD actions for Document model.
 */
class DocumentController extends \admin\components\AdminController
{
	public $modelClass = 'app\models\Document';
	public $searchModelClass = 'app\models\search\DocumentSearch';

	public $sectionTitle = 'Документы';

}
