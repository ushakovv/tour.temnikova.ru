<?php

use yii\helpers\Html;
use admin\components\ActiveForm;
use core\components\AdminHtml;

/**
 * @var yii\web\View $this
 * @var app\models\VkAlbum $model
 * @var admin\components\ActiveForm $form
 */
?>

<div class="vk-album-form">

    <?php $form = ActiveForm::begin([
        'options' => [
            'enctype' => 'multipart/form-data'
        ]
    ]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'album_id')->textInput(['maxlength' => 255]) ?>

    <div class="form-group">
        <p class="col-sm-offset-3 col-sm-6 text-info">
            Откройте фотоальбом во Вконтакте, скопируйте ссылку на альбом, все после "https://vk.com/album"
        </p>
        <p class="col-sm-offset-3 col-sm-6 text-info">
            Пример: https://vk.com/album<span class="text-red">-72665113_248190453</span>
        </p>
    </div>

    <?=  $form->buttons([
        [ 'title' => $model->isNewRecord ? 'Добавить' : 'Сохранить', 'type' => 'green']
    ]) ?>

    <?php ActiveForm::end(); ?>

</div>
