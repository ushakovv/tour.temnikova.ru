<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use core\components\AdminHtml;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var \app\models\LoginForm $model
 */
$this->title = 'AUTHORIZATION';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">

    <?= AdminHtml::header($this->title) ?>

    <!--<p>Please fill out the following fields to login:</p>-->

    <div class="container-fluid container-fullw bg-white">
        <div class="row">
            <div class="col-lg-5">
                <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
                    <?= $form->field($model, 'username') ?>
                    <?= $form->field($model, 'password')->passwordInput() ?>

                    <div class="form-group">
                        <?= Html::submitButton('Login', ['class' => 'btn btn-green', 'name' => 'login-button']) ?>
                    </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
