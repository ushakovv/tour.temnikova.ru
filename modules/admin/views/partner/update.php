<?php

use yii\helpers\Html;
use yii\helpers\Url;
use core\components\AdminHtml;

/**
 * @var yii\web\View $this
 * @var app\models\Partner $model
 */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => $this->context->sectionTitle , 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->title;
?>
<div class="partner-update">

	<?=  AdminHtml::header($this->title, true); ?>

    <div class="container-fluid container-fullw bg-white">
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>

</div>
