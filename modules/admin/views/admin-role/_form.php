<?php

use yii\helpers\Html;
use admin\components\ActiveForm;
use app\models\AdminSectionGroup;

/**
 * @var yii\web\View $this
 * @var app\models\AdminRole $model
 * @var admin\components\ActiveForm $form
 */
?>

<div class="admin-role-form">

    <?php $form = ActiveForm::begin([
		'options' => [
			'enctype' => 'multipart/form-data'
		]
	]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 50]) ?>

	<?php
	    if($model->isNewRecord) {
		    $html = '* Сохраните чтобы назначить права';
	    }
	    else {
		    $adminSectionGroup = new AdminSectionGroup();
		    $html = Yii::$app->view->renderFile(\Yii::getAlias('@admin').'/views/admin-role/permissions.twig', [
			    'data' => $adminSectionGroup->getCmsStructure($model->id),
		    ]);
	    }
	    echo $form->field($model, 'permissions')->html($html);
    ?>

    <?=  $form->buttons([
		[ 'title' => $model->isNewRecord ? 'Добавить' : 'Сохранить', 'type' => 'primary']
	]) ?>

    <?php ActiveForm::end(); ?>

</div>

<?php

$sScript = <<< EOF

	$('input.full_rules').change(function(){
		var _t = $(this);
		var checked = false;
		if(_t.is(':checked')) {
			checked = true;
		}
		_t.parents('tr').find('input.rules').prop('checked', checked);
	});

	$('input.rules').change(function(){
		var _t = $(this);
		var checked = true;
		if(_t.parents('tr').find('input.rules:checked').length!=4) {
			checked = false;
		}
		_t.parents('tr').find('input.full_rules').prop('checked', checked);
	});

	checkAll = function(checked) {
		$('#permissions_block input').prop('checked', checked);
		return false;
	}
EOF;

$this->registerJs($sScript, yii\web\View::POS_READY); ?>