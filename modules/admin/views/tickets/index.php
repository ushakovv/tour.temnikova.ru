<?php

use yii\helpers\Html;
use admin\components\GridView;
use yii\helpers\ArrayHelper;
use admin\components\ColumnHelper;
use core\components\AdminHtml;
use app\models\Tickets;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\TicketsSearch $searchModel
 */

$this->title = $this->context->sectionTitle;

?>
<div class="tickets-index">

    <?=  AdminHtml::header($this->context->sectionTitle) ?>

<div class="container-fluid container-fullw bg-white">

    <p>
        <?= AdminHtml::button('Добавить', ['create'], 'plus') ?>
    </p>

	<?php $ch = new ColumnHelper(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
			//$ch->serial(),

            $ch->dropdownByModel('e_id', 'Events', $valueField = 'id', $titleField = 'place'),
            $ch->text('email'),
            $ch->text('phone'),
            $ch->text('name'),
            $ch->text('dt'),

            $ch->actions(true, true),
        ],
    ]); ?>
</div>

</div>
