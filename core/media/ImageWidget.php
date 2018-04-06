<?php

namespace core\media;

use core\components\UpdateScenario;

class ImageWidget extends \yii\widgets\InputWidget
{

    public function run()
    {
        $value = $this->model->{$this->attribute};

        UpdateScenario::registerHandler($this->attribute, '\core\media\ImageManager', 'processPostField');

        echo $this->render('imageWidget', [
            'value' => $value,
            'model_id' => $this->model->id,
            'attribute' => $this->attribute
        ]);
    }

    public function getImageType()
    {
        return $this->model->tableName() . "." . $this->attribute;
    }

    public static function renderVariants($type, $value, $attribute, $model_id)
    {
        $modalId = $attribute . "-modal";
        if($value){
            $variants = \Yii::$app->imageManager->getVariants($type, $value);
        } else {
            $variants = [];
        }
        $html = "";
        foreach ($variants as $vKey => $variant) {
            $html .= "<div class='mediaimage__block'><a href='$variant' target='_blank' data-modal='$modalId' data-variant='$vKey' data-attribute='$attribute' data-url='$variant' data-id='$value' data-object_id='$model_id'><span class='mediaimage__info'>$vKey</span><img src='$variant' class='img-thumbnail' style='max-height:100px'></a></div>";
            $html .= "&nbsp;&nbsp;";
        }
        return $html;
    }

} 