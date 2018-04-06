<?php

namespace core\widgets;

use app\components\ActiveRecord;
use core\components\UpdateScenario;
use core\models\ContentBlocksModel;
use yii\base\Model;
use yii\base\Widget;
use yii\bootstrap\Html;
use yii\bootstrap\Tabs;
use yii\helpers\Url;

class ContentBlocksWidget extends Widget
{
    public $form;

    public $object_type;
    public $object_id;

    /**
     * @var ActiveRecord
     */
    public $model;

    protected $cbModel;

    private function _getLanguages()
    {
        return require(\Yii::getAlias('@app') . "/config/languages.php");
    }

    private function _getConfig()
    {
        $config = require(\Yii::getAlias('@app') . "/config/content_blocks.php");
        if(isset($config[$this->object_type])){
            if($this->object_id){
                if(isset($config[$this->object_type][$this->object_id])){
                    return $config[$this->object_type][$this->object_id];
                }
            } else {
                return $config[$this->object_type];
            }
        }
    }

    public function run()
    {
        if(!$this->object_type && !$this->object_id){
            $this->object_type = $this->model->tableName();
            $this->object_id = $this->model->id;
        }
        $languages = $this->_getLanguages();

        if($objConfig = $this->_getConfig()){
            $cbModel = ContentBlocksModel::fetch($this->object_type, $this->object_id);
            $this->cbModel = $cbModel;
            UpdateScenario::registerHandler(null, ContentBlocksModel::className(), 'processPostFields', [], true);
            $tabs = [];
            $aplang = @$_COOKIE['aplang'];
            foreach ($languages as $i => $lang){
                $label = strtoupper($lang);
                $tabs[] = [
                    'label' => strtoupper($lang),
                    'content' => $this->renderItems($objConfig, $lang),
                    'active' => $aplang ? ($aplang == $label) : ($i === 0)
                ];
            }
            return Tabs::widget([
                'options' => [
                    'class' => 'js-lang-tabs',
                ],
                'items' => $tabs
            ]);
        }
    }

    public function renderItems($items, $lang)
    {
        $form = $this->form;
        $html = "";
        foreach ($items as $key => $obj){
            if(substr($key, 0, 5) == 'link:'){
                $html .= Html::tag("div",
                    Html::a("<span class=\"glyphicon glyphicon-new-window\"></span>" . substr($key, 5) . " ",
                        Url::toRoute($obj),
                        ["target" => "_blank", "style" => "font-size: 16px"]
                    ),
                    ["style" => "padding: 20px"]
                );
            } else if(substr($key, 0, 6) == 'group:'){
                $html .= Html::tag(
                    'fieldset',
                    Html::tag('legend', substr($key, 6)) . $this->renderItems($obj, $lang)
                );
            } else {
                if(count($obj) == 3){
                    list($title, $type, $options) = $obj;
                } else {
                    list($title, $type) = $obj;
                    $options = [];
                }
                if($type == "text"){
                    $html .= $form->field($this->cbModel, $key . "_" . $lang)->label($title)->textInput($options);
                }
                if($type == "textarea"){
                    $html .= $form->field($this->cbModel, $key . "_" . $lang)->label($title)->textarea($options);
                }
                if($type == "redactor"){
                    $html .= $form->field($this->cbModel, $key . "_" . $lang)->label($title)->redactor($options);
                }
            }
        }
        return $html;
    }

}