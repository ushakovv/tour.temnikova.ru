<?php

use yii\helpers\Html;
use admin\components\ActiveForm;
use core\components\AdminHtml;

/**
 * @var yii\web\View $this
 * @var app\models\City $model
 * @var admin\components\ActiveForm $form
 */
?>

<div class="city-form">

    <?php $form = ActiveForm::begin([
        'options' => [
            'enctype' => 'multipart/form-data'
        ]
    ]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'coords_x')->textInput(['maxlength' => 3]) ?>

    <?= $form->field($model, 'coords_y')->textInput(['maxlength' => 3]) ?>

    <?= $form->field($model, 'status')->checkbox() ?>

    <?=  $form->buttons([
        [ 'title' => $model->isNewRecord ? 'Добавить' : 'Сохранить', 'type' => 'green']
    ]) ?>

    <?php ActiveForm::end(); ?>

</div>
