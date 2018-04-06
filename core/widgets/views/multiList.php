<?php
use yii\web\JsExpression;
use kartik\widgets\Select2;
?>

<style>
    .multilist-item {
        border: solid 1px #dadada;
        border-radius: 5px;
        color: black;
        background-color: #f7f7f7;
        padding: 3px 8px;
        margin-bottom: 5px;
    }
</style>
<div class="multilist-container">

<?php

echo '<div class="multilist-items">';
foreach ($items as $item){
    echo $this->context->renderListItem($item, $name, is_array($modelClass));
}
echo '</div>';

$renderUrl = \yii\helpers\Url::to(['render-multilist-item', 'model' => $modelClass, 'formName' => $name]);

echo Select2::widget([
    'options' => ['placeholder' => 'Добавить...'],
    'name' => str_replace("]", "_input]", $name),
    'pluginOptions' => [
        'allowClear' => true,
        'minimumInputLength' => 3,
        'language' => [
            'errorLoading' => new \yii\web\JsExpression("function () { return 'Загрузка...'; }"),
        ],
        'ajax' => [
            'url' => \yii\helpers\Url::to(['items-json', 'model' => $modelClass]),
            'dataType' => 'json',
            'data' => new JsExpression('function(params) { return {q:params.term}; }')
        ],
        'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
        'templateResult' => new JsExpression('function(city) { return city.text; }'),
        'templateSelection' => new JsExpression('function (city) { return city.text; }'),
    ],
    'pluginEvents' => [
        "select2:select" => "function(evt){ 
            var id = $(evt.currentTarget).val();
             $(evt.currentTarget).select2('val', ''); 
             $.get('$renderUrl', {id: id}, function(data){
                $(evt.currentTarget).parents('.multilist-container').find('.multilist-items').append(data); 
             }); 
          }"
    ]
]);
?>
</div>