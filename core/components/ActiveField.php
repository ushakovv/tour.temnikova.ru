<?php

namespace core\components;

use conquer\codemirror\CodemirrorWidget;
use core\components\TreeViewInput;
use core\media\FileWidget;
use kartik\widgets\Select2;
use mihaildev\elfinder\ElFinder;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
use Yii;
use yii\helpers\Html;

class ActiveField extends \yii\bootstrap\ActiveField
{

    public $checkboxTemplate = "<div class=\"checkbox clip-check check-primary\">\n{beginLabel}\n{input}\n{labelTitle}\n{endLabel}\n{error}\n{hint}\n</div>";
    public $horizontalCheckboxTemplate = "{beginWrapper}\n<div class=\"checkbox clip-check check-primary\">\n{input}\n{beginLabel}\n{labelTitle}\n{endLabel}\n</div>\n{error}\n{endWrapper}\n{hint}";

    public function redactor($config = [])
    {
        $this->widget('yii\imperavi\Widget', $config);
        return $this;
    }

    public function ckeditor($editorOptions = [], $containerOptions = [])
    {
        $editorOptions = array_merge(
            [
                'preset' => 'standard',
                'inline' => false,
                'height' => 300,
                'allowedContent' => true
            ],
            $editorOptions
        );
        $this->widget('core\ckeditor\CKEditor', [
            'editorOptions' => ElFinder::ckeditorOptions(['elfinder', 'path' => $this->model->getModelFolder()], $editorOptions),
            'containerOptions' => $containerOptions
        ]);
        return $this;
    }

    public function mediaImage()
    {
        $this->widget('\core\media\ImageWidget');
        return $this;
    }

    /**
     * @param ActiveQuery $query
     * @param string $valueField
     * @param string $titleField
     * @return $this
     */
    public function dropdownListByQuery($query, $valueField = 'id', $titleField = 'name')
    {
        $array = [null => "нет"] + ArrayHelper::map($query->all() , $valueField, $titleField );
        return $this->dropDownList($array);
    }

    public function dropdownTreeByQuery($query)
    {
        return $this->widget(TreeViewInput::className(), [
            'query' => $query,
            //'headingOptions'    => ['label' => 'Categories'],
            'headerTemplate' => "",
            'asDropdown' => true,
            'multiple' => false,
            'fontAwesome' => true,
            'rootOptions' => [
                'label' => '<i class="fa fa-tree"></i>',
                'class' => 'text-success hide'
            ],                                      // custom root label
            //'options'         => ['disabled' => true],
        ]);
    }

    public function datetime($config = [])
    {
        return $this->widget('\kartik\widgets\DateTimePicker', $config);
    }

    public function time($config = [])
    {
        return $this->widget('\kartik\widgets\TimePicker', $config);
    }

    /**
     * @param string $validator file|image
     * @param array $validatorOptions http://www.yiiframework.com/doc-2.0/guide-tutorial-core-validators.html#file
     * @return $this
     */
    public function mediaFile($validator = "file", $validatorOptions = [])
    {

        return $this->widget(FileWidget::className(), [
            'validator' => $validator,
            'validatorOptions' => $validatorOptions
        ]);
    }

    public function intInput()
    {
        if ($this->model->{$this->attribute} === null) {
            $this->model->{$this->attribute} = 0;
        };

        echo $this->widget('kartik\widgets\TouchSpin', [
            'options' => ['placeholder' => '...']
        ]);
    }

    public function date($options = [])
    {
        return $this->widget('\kartik\widgets\DatePicker', ArrayHelper::merge($options, [
            'pluginOptions' => [
                'format' => 'yyyy-mm-dd',
                'todayHighlight' => true
            ]
        ]));
    }

    public function passwordEye($value = '')
    {
        $this->adjustLabelFor($this->inputOptions);

        $name = $this->attribute;
        $html = Yii::$app->view->renderFile(Yii::getAlias('@admin') . '/views/layouts/passwordEye.php', [
            'name' => $name,
            'value' => $value,
        ]);
        $this->parts['{input}'] = $html;

        return $this;
    }

    public function dropDownListByModel($className, $valueField = 'id', $titleField = 'name', $orderBy = null)
    {
        $fullClassName = ClassHelper::getClassPath($className);
        $array = ArrayHelper::map( $fullClassName::find()->orderBy($orderBy)->all() , $valueField, $titleField );
        return $this->dropDownList( $array );
    }

    public function html($_html = null)
    {
        if (is_null($_html)) {
            $field = $this->attribute;
            $html = $this->model->$field;
        } else {
            $html = $_html;
        }

        if ($html == '' || is_null($html)) {
            $html = '<span class="not-set">(не задано)</span>';
        }
        $this->adjustLabelFor($this->inputOptions);
        $this->parts['{input}'] = $html;

        return $this;
    }

    public function codeMirror($preset = 'php', $options = [])
    {
        return $this->widget(CodemirrorWidget::className(), [
            'preset' => $preset,
            'options' => $options,
        ]);
    }

    public function lang()
    {
        return $this->dropDownList(['en' => 'en', 'ru' => 'ru']);
    }


} 