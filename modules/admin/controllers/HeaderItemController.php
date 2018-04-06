<?php

namespace admin\controllers;

use Yii;
use app\models\HeaderItem;
use app\models\search\HeaderItemSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * HeaderItemController implements the CRUD actions for HeaderItem model.
 */
class HeaderItemController extends \admin\components\AdminController
{
    public $modelClass = 'app\models\HeaderItem';
    public $searchModelClass = 'app\models\search\HeaderItemSearch';

    public $sectionTitle = 'Header пункты';

}
 