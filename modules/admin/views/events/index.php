<?php

use yii\helpers\Html;
use admin\components\GridView;
use yii\helpers\ArrayHelper;
use admin\components\ColumnHelper;
use core\components\AdminHtml;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\EventsSearch $searchModel
 */

$this->title = $this->context->sectionTitle;

?>
<div class="events-index">

    <?=  AdminHtml::header($this->context->sectionTitle) ?>

<div class="container-fluid container-fullw bg-white">

    <p>
        <?= AdminHtml::button('Create Events', ['create'], 'plus') ?>
    </p>

	<?php $ch = new ColumnHelper(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
			//$ch->serial(),

            $ch->dropdownByModel('city_id', 'City', $valueField = 'id', $titleField = 'name'),
            $ch->text('tour_desc'),
            $ch->text('tour_dt'),
            $ch->checkbox('was'),
            $ch->checkbox('status'),
            $ch->text('p_dt'),
            $ch->text('p_time'),
            $ch->text('p_auth'),

            $ch->actions(true, true),
        ],
    ]); ?>
</div>

</div>
