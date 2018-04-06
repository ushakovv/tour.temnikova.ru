<?php

use yii\helpers\Html;
use yii\helpers\Url;
use core\components\AdminHtml;

/**
 * @var yii\web\View $this
 * @var app\models\Events $model
 */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => $this->context->sectionTitle , 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id;
?>
<div class="events-update">

	<?=  AdminHtml::header($this->title, true); ?>

    <div class="container-fluid container-fullw bg-white">
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>

</div>
