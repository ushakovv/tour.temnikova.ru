<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\models\search\EventsSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="events-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'tour_id') ?>

    <?= $form->field($model, 'city_id') ?>

    <?= $form->field($model, 'tour_desc') ?>

    <?= $form->field($model, 'tour_dt') ?>

    <?php // echo $form->field($model, 'image') ?>

    <?php // echo $form->field($model, 'map_x') ?>

    <?php // echo $form->field($model, 'map_y') ?>

    <?php // echo $form->field($model, 'vk_event') ?>

    <?php // echo $form->field($model, 'was') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'sort') ?>

    <?php // echo $form->field($model, 'p_alias') ?>

    <?php // echo $form->field($model, 'p_dt') ?>

    <?php // echo $form->field($model, 'p_time') ?>

    <?php // echo $form->field($model, 'p_auth') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
