<?php

use yii\helpers\Html;
use admin\components\GridView;
use \yii\helpers\ArrayHelper;
use \admin\components\ColumnHelper;
use core\components\AdminHtml;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\AdminRoleSearch $searchModel
 */

$this->title = $this->context->sectionTitle;

?>
<div class="admin-role-index">

    <?=  AdminHtml::header($this->context->sectionTitle) ?>

    <div class="container-fluid container-fullw bg-white">

    <p>
        <?= $crud_permissions['c']?Html::a('Добавить Роль в CMS', ['create'], ['class' => 'btn btn-primary']):'' ?>
    </p>

	<?php $ch = new ColumnHelper(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
			//$ch->serial(),

            $ch->int('id'),
            $ch->text('name'),

	        $ch->actions($crud_permissions['u']?true:false, $crud_permissions['d']?true:false),
        ],
    ]); ?>
</div>

</div>
