<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 21.11.2016
 * Time: 12:47
 */

namespace app\commands;

use yii\console\Controller;
use app\components\InstagramChangeImage;

class InstagramChangeImageController extends Controller
{

    public function actionGenerate()
    {
        $t = new InstagramChangeImage();
        $t->generate();
    }

    public function actionHidden()
    {
        $t = new InstagramChangeImage();
        $t->hiddenNullPost();
    }
}