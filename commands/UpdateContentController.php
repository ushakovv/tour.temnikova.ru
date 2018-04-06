<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\models\Pages;
use yii\console\Controller;

class UpdateContentController extends Controller
{
    public function actionIndex()
    {
        $pages = Pages::find()->all();
        foreach ($pages as $page){
            $page->updateContent();
            echo ".";
        }
    }
}
