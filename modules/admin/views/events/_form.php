<?php

use yii\helpers\Html;
use admin\components\ActiveForm;
use core\components\AdminHtml;

/**
 * @var yii\web\View $this
 * @var app\models\Events $model
 * @var admin\components\ActiveForm $form
 */
?>

<div class="events-form">

    <?php $form = ActiveForm::begin([
        'options' => [
            'enctype' => 'multipart/form-data'
        ]
    ]); ?>

    <?= $form->field($model, 'tour_id')->textInput(['maxlength' => 11]) ?>

    <?= $form->field($model, 'city_id')->dropDownListByModel(\app\models\City::className()) ?>

    <?= $form->virtualField($model, 'Кассы')
        ->multiSelectDropdownListByModel(\app\models\Cashbox::className(),'id','name','events2cashbox', 'e_id', 'c_id') ?>

    <?= $form->field($model, 'tour_desc')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'tour_dt')->textInput(['maxlength' => 150]) ?>

    <?= $form->field($model, 'album_id')->textInput(['maxlength' => 150]) ?>

    <?= $form->field($model, 'image')->mediaFile() ?>

    <?= $form->field($model, 'map_x')->textInput(['maxlength' => 11]) ?>

    <?= $form->field($model, 'map_y')->textInput(['maxlength' => 11]) ?>

    <?= $form->field($model, 'place')->textInput(['maxlength' => 150]) ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'vk_event')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'was')->checkbox() ?>

    <?= $form->field($model, 'status')->checkbox() ?>

    <?= $form->field($model, 'sort')->intInput() ?>

    <?= $form->field($model, 'p_alias')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'p_dt')->textInput() ?>

    <?= $form->field($model, 'p_time')->textInput() ?>

    <?= $form->field($model, 'p_auth')->textInput(['maxlength' => 255]) ?>

    <?=  $form->buttons([
        [ 'title' => $model->isNewRecord ? 'Добавить' : 'Сохранить', 'type' => 'green']
    ]) ?>

    <?php ActiveForm::end(); ?>

</div>
