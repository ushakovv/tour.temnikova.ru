<?php

use yii\helpers\Html;
use admin\components\ActiveForm;
use core\components\AdminHtml;

/**
 * @var yii\web\View $this
 * @var app\models\SocialGroup $model
 * @var admin\components\ActiveForm $form
 */
?>

<div class="social-group-form">

    <?php $form = ActiveForm::begin([
        'options' => [
            'enctype' => 'multipart/form-data'
        ]
    ]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 150]) ?>

    <?= $form->field($model, 'link')->textInput(['maxlength' => 150]) ?>

    <?= $form->field($model, 'logo')->mediaFile() ?>

    <?= $form->field($model, 'logo_active')->mediaFile() ?>

    <?= $form->field($model, 'status')->checkbox() ?>

    <?= $form->field($model, 'sort')->intInput() ?>

    <?=  $form->buttons([
        [ 'title' => $model->isNewRecord ? 'Добавить' : 'Сохранить', 'type' => 'green']
    ]) ?>

    <?php ActiveForm::end(); ?>

</div>
