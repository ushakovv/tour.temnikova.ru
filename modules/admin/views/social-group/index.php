<?php

use yii\helpers\Html;
use admin\components\GridView;
use yii\helpers\ArrayHelper;
use admin\components\ColumnHelper;
use core\components\AdminHtml;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\SocialGroupSearch $searchModel
 */

$this->title = $this->context->sectionTitle;

?>
<div class="social-group-index">

    <?=  AdminHtml::header($this->context->sectionTitle) ?>

<div class="container-fluid container-fullw bg-white">

    <p>
        <?= AdminHtml::button('Создать группу', ['create'], 'plus') ?>
    </p>

	<?php $ch = new ColumnHelper(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
			//$ch->serial(),
            $ch->text('name'),
            $ch->text('link'),
            $ch->checkbox('status'),
            $ch->checkbox('sort'),

            $ch->actions(true, true),
        ],
    ]); ?>
</div>

</div>
