<?php

use yii\helpers\Html;
use admin\components\GridView;
use \yii\helpers\ArrayHelper;
use \admin\components\ColumnHelper;
use core\components\AdminHtml;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\AdminUserSearch $searchModel
 */

$this->title = $this->context->sectionTitle;

?>
<div class="admin-user-index">

    <?=  AdminHtml::header($this->context->sectionTitle) ?>

    <div class="container-fluid container-fullw bg-white">

    <p>
        <?= $crud_permissions['c']?Html::a('Добавить Администратора', ['create'], ['class' => 'btn btn-primary']):'' ?>
    </p>

	<?php $ch = new ColumnHelper(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
			//$ch->serial(),

            $ch->int('id'),
	        $ch->dropdownByModel('role_id', 'AdminRole', 'id', 'name'),
	        $ch->text('username'),
            $ch->text('email'),
            $ch->checkbox('is_get_notice'),
            $ch->text('description'),
            $ch->text('count_login_errors'),
            $ch->text('dt_change_password'),

	        $ch->actions($crud_permissions['u']?true:false, $crud_permissions['d']?true:false),
        ],
    ]); ?>
        </div>

</div>
