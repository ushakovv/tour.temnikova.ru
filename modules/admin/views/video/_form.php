<?php

use yii\helpers\Html;
use admin\components\ActiveForm;
use core\components\AdminHtml;

/**
 * @var yii\web\View $this
 * @var app\models\Video $model
 * @var admin\components\ActiveForm $form
 */
?>

<div class="video-form">

    <?php $form = ActiveForm::begin([
        'options' => [
            'enctype' => 'multipart/form-data'
        ]
    ]); ?>

    <?= $form->field($model, 'city')->textInput(['maxlength' => 150]) ?>

    <?= $form->field($model, 'source')->textInput(['maxlength' => 150]) ?>
    <div class="form-group">
        <p class=" col-sm-offset-3 col-sm-6 text-info">
            Откройте видео Вконтакте, нажмите "Поделиться", перейдите на "Экспортировать" и скопируйте ссылку в блоке iframe, начиная с "//vk.com"
        </p>
    </div>

    <?= $form->field($model, 'date')->datetime() ?>

    <?= $form->field($model, 'enabled')->checkbox() ?>

    <?= $form->field($model, 'ord')->intInput() ?>

    <?=  $form->buttons([
        [ 'title' => $model->isNewRecord ? 'Добавить' : 'Сохранить', 'type' => 'green']
    ]) ?>

    <?php ActiveForm::end(); ?>

</div>
