<?php

use yii\helpers\Html;
use admin\components\GridView;
use yii\helpers\ArrayHelper;
use admin\components\ColumnHelper;
use core\components\AdminHtml;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\PagesSearch $searchModel
 */

$this->title = $this->context->sectionTitle;

?>
<div class="pages-index">

    <?=  AdminHtml::header($this->context->sectionTitle) ?>

<div class="container-fluid container-fullw bg-white">

<!--    <p>-->
<!--        --><?//= AdminHtml::button('Create Pages', ['create'], 'plus') ?>
<!--    </p>-->

	<?php $ch = new ColumnHelper(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
			//$ch->serial(),

            $ch->int('id'),
            $ch->text('controller_name'),
//            $ch->checkbox('type'),
            $ch->text('url'),
            $ch->text('name'),
            $ch->text('title'),
            $ch->text('description'),
            $ch->text('keywords'),

            $ch->actions(true, false),
        ],
    ]); ?>
</div>

</div>
