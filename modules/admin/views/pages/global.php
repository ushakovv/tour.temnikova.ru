<?php

use yii\helpers\Html;
use admin\components\ActiveForm;
use core\components\AdminHtml;

/**
 * @var yii\web\View $this
 * @var admin\components\ActiveForm $form
 */
?>

<div class="pages-update">

    <?=  AdminHtml::header("Common texts", true); ?>

    <div class="container-fluid container-fullw bg-white">

        <div class="pages-form">

            <?php $form = ActiveForm::begin([
                'options' => [
                    'enctype' => 'multipart/form-data'
                ]
            ]); ?>

            <?= \core\widgets\ContentBlocksWidget::widget([
                'object_type' => 'global',
                'form' => $form
            ]);
            ?>

            <?=  $form->buttons([
                [ 'title' => 'Сохранить', 'type' => 'green']
            ]) ?>

            <?php ActiveForm::end(); ?>

        </div>
    </div>

</div>
