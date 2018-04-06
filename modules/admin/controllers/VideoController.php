<?php

namespace admin\controllers;

/**
 * VideoController implements the CRUD actions for Video model.
 */
class VideoController extends  \admin\components\AdminController
{
	public $modelClass = 'app\models\Video';
	public $searchModelClass = 'app\models\search\VideoSearch';

	public $sectionTitle = 'Видео';

}
