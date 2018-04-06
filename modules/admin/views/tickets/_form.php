<?php

use yii\helpers\Html;
use admin\components\ActiveForm;
use core\components\AdminHtml;

/**
 * @var yii\web\View $this
 * @var app\models\Tickets $model
 * @var admin\components\ActiveForm $form
 */
?>

<div class="tickets-form">

    <?php $form = ActiveForm::begin([
        'options' => [
            'enctype' => 'multipart/form-data'
        ]
    ]); ?>

    <?= $form->field($model, 'e_id')->textInput(['maxlength' => 10]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => 150]) ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => 50]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 50]) ?>

    <?= $form->field($model, 'dt')->datetime() ?>

    <?=  $form->buttons([
        [ 'title' => $model->isNewRecord ? 'Добавить' : 'Сохранить', 'type' => 'green']
    ]) ?>

    <?php ActiveForm::end(); ?>

</div>
