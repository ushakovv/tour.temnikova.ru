<?php

use yii\helpers\Html;
use admin\components\ActiveForm;
use core\components\AdminHtml;

/**
 * @var yii\web\View $this
 * @var app\models\HeaderItem $model
 * @var admin\components\ActiveForm $form
 */
?>

<div class="header-item-form">

    <?php $form = ActiveForm::begin([
        'options' => [
            'enctype' => 'multipart/form-data'
        ]
    ]); ?>

    <?= $form->field($model, 'id_group')->dropDownListByModel('HeaderGroup', 'id' , 'name') ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 150]) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => 150]) ?>

    <?= $form->field($model, 'logo')->textInput(['maxlength' => 150]) ?>

    <?= $form->field($model, 'link')->textInput(['maxlength' => 150]) ?>

    <?= $form->field($model, 'enabled')->checkbox() ?>

    <?= $form->field($model, 'ord')->intInput() ?>

    <?=  $form->buttons([
        [ 'title' => $model->isNewRecord ? 'Добавить' : 'Сохранить', 'type' => 'green']
    ]) ?>

    <?php ActiveForm::end(); ?>

</div>
