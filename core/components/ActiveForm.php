<?php

namespace core\components;


use app\components\ActiveRecord;
use core\media\FileGalleryWidget;
use core\widgets\ContentBlocksWidget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\ActiveFormAsset;

class ActiveForm extends \yii\bootstrap\ActiveForm
{

    public $fieldConfig = [
        'class' => '\admin\components\ActiveField'
    ];
    public $layout = 'horizontal';

    public $enableClientValidation = false;

    /**
     * Generates a form field.
     * A form field is associated with a model and an attribute. It contains a label, an input and an error message
     * and use them to interact with end users to collect their inputs for the attribute.
     * @param ActiveRecord $model the data model
     * @param string $attribute the attribute name or expression. See [[Html::getAttributeName()]] for the format
     * about attribute expression.
     * @param array $options the additional configurations for the field object
     * @return \admin\components\ActiveField the created ActiveField object
     * @see fieldConfig
     */
    public function field($model, $attribute, $options = [])
    {
        return \Yii::createObject(array_merge($this->fieldConfig, $options, [
            'model' => $model,
            'attribute' => $attribute,
            'form' => $this,
        ]));
    }

    /**
     * @param $label
     * @param array $options
     * @return \core\components\VirtualField
     */
    public function virtualField($model, $label, $options = [])
    {
        $id = 'vf' . rand(0, 99999);
        return \Yii::createObject(array_merge(['class' => '\core\components\VirtualField'], $options, [
            'model' => $model,
            'form' => $this,
            'attribute' => $id,
            'labelOptions' => [
                'label' => $label,
                'for' => $id
            ]
        ]));
    }

    public function buttons($buttons)
    {
        echo '<div class="form-group form-actions"><div class="col-sm-6 col-sm-offset-3">';
        //echo '<div class="well">';
        foreach ($buttons as $button) {
            $options = isset($button['options']) ? $button['options'] : [];
            $type = isset($button['type']) ? $button['type'] : 'default';
            echo Html::submitButton($button['title'], ArrayHelper::merge(['class' => 'btn btn-' . $type], $options));
            echo "&nbsp;";
        }
        echo '</div></div>';
    }

    public function fileGallery($model, $title = 'Файлы', $subtype = 'main', $options = [])
    {
        return FileGalleryWidget::widget([
            'model' => $model,
            'title' => $title,
            'subtype' => $subtype,
            'options' => $options
        ]);
    }

    public function contentBlocks($model)
    {
        return ContentBlocksWidget::widget([
            'model' => $model,
            'form' => $this
        ]);
    }


    public function run()
    {
        echo "<input type='hidden' id='updateScenario' name='updateScenario' value='" . UpdateScenario::serialize() . "'>";
        parent::run();
    }


} 