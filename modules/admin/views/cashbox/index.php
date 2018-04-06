<?php

use yii\helpers\Html;
use admin\components\GridView;
use yii\helpers\ArrayHelper;
use admin\components\ColumnHelper;
use core\components\AdminHtml;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\CashboxSearch $searchModel
 */

$this->title = $this->context->sectionTitle;

?>
<div class="cashbox-index">

    <?=  AdminHtml::header($this->context->sectionTitle) ?>

<div class="container-fluid container-fullw bg-white">

    <p>
        <?= AdminHtml::button('Добавить онлайн-кассу', ['create'], 'plus') ?>
    </p>

	<?php $ch = new ColumnHelper(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
			//$ch->serial(),
            $ch->text('name'),
            $ch->text('front_name'),
            $ch->text('link'),
            $ch->text('phone'),

            $ch->actions(true, true),
        ],
    ]); ?>
</div>

</div>
