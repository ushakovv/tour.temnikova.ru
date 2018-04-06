<?php

use yii\helpers\Html;
use admin\components\ActiveForm;
use core\components\AdminHtml;

/**
 * @var yii\web\View $this
 * @var app\models\Pages $model
 * @var admin\components\ActiveForm $form
 */
?>

<div class="pages-form">

    <?php $form = ActiveForm::begin([
        'options' => [
            'enctype' => 'multipart/form-data'
        ]
    ]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 50]) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => 200]) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => 200]) ?>

    <?= $form->field($model, 'keywords')->textInput(['maxlength' => 200]) ?>

    <?= $form->contentBlocks($model) ?>

    <?=  $form->buttons([
        [ 'title' => $model->isNewRecord ? 'Добавить' : 'Сохранить', 'type' => 'green']
    ]) ?>

    <?php ActiveForm::end(); ?>

</div>
