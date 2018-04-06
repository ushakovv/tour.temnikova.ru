<?php

use yii\helpers\Html;
use admin\components\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\models\AdminUser $model
 * @var admin\components\ActiveForm $form
 */
?>

<div class="admin-user-form">

    <?php $form = ActiveForm::begin([
		'options' => [
			'enctype' => 'multipart/form-data'
		]
	]); ?>

    <?= $form->field($model, 'role_id')->dropdownListByQuery(\app\models\AdminRole::find(), 'id', 'name') ?>
    
    <?= $form->field($model, 'username')->textInput(['maxlength' => 256, 'autocomplete' => 'off']) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => 128]) ?>

    <?= $form->field($model, 'password')->passwordEye() ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'is_get_notice')->checkbox() ?>

    <?=  $form->buttons([
		[ 'title' => $model->isNewRecord ? 'Добавить' : 'Сохранить', 'type' => 'primary']
	]) ?>

    <?php ActiveForm::end(); ?>

</div>
