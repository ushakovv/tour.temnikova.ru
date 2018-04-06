<?php

use yii\helpers\Html;
use yii\helpers\Url;
use core\components\AdminHtml;

/**
 * @var yii\web\View $this
 * @var app\models\City $model
 */

$this->title = 'Create City';
$this->params['breadcrumbs'][] = ['label' => $this->context->sectionTitle, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="city-create">

    <?=  AdminHtml::header($this->title, true); ?>

    <div class="container-fluid container-fullw bg-white">
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>

</div>
