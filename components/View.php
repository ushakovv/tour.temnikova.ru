<?php

namespace app\components;


use app\models\Pages;
use app\models\Subdivision;
use app\models\Tool;
use app\models\User;
use core\components\ContentManager;
use yii\helpers\Json;

class View extends \yii\web\View
{
    public $bodyClass = "";
    public $headerTemplate = "header.default.twig";

    protected $_js = [];
    protected $_css = [];

    public function addCss($url, $options = []){
        if($url){
            if(is_array($url)){
                foreach($url as $item){
                    $this->_css[] = ['url' => $item, 'options' => $options];
                }
            }
            else{
                $this->_css[] = ['url' => $url, 'options' => $options];
            }
        }
    }

    public function printCss(){
        $result = '';
        foreach($this->_css as $item){
            $result .= \yii\helpers\Html::cssFile($item['url'],$item['options']) . PHP_EOL;
        }
        return $result;
    }

    public function addJs($url,$options = []){
        if($url){
            if(is_array($url)){
                foreach($url as $item){
                    $this->_js[] = ['url' => $item, 'options' => $options];
                }
            }
            else{
                $this->_js[] = ['url' => $url, 'options' => $options];
            }

        }
    }

    public function printJs(){
        $result = '';
        $rev = ContentManager::getRev();
        foreach($this->_js as $item){
            $result .= \yii\helpers\Html::jsFile($item['url'] . "?" . $rev ,$item['options']) . PHP_EOL;
        }
        return $result;
    }

    public $mapCallback = null;

    public function setHeaderTemplate($template)
    {
        $this->headerTemplate = $template;
    }

    public function addBodyClass($class){
        $this->bodyClass .= " " . $class;
    }
    

    public function getUser()
    {
        return $this->context->getUser();
    }

    public function getLang()
    {
        return \Yii::$app->language;
    }

    public function getNameCtrlAction()
    {
        return \Yii::$app->controller->id . '/' . \Yii::$app->controller->action->id;
    }

    public function getApp()
    {
        return \Yii::$app;
    }


    public function getPagesByColumn($column)
    {
        return Pages::getByColumn($column);
        //$menu[1] = Pages::findAll(['enabled_' . \Yii::$app->language => 1, 'parent_id' => 1, 'column' => 1]);
    }

    public function getRootPages()
    {
        return Pages::getByParentId(1);
        //$menu[1] = Pages::findAll(['enabled_' . \Yii::$app->language => 1, 'parent_id' => 1, 'column' => 1]);
    }

    public function getPagesByParentId($parentId)
    {
        return Pages::getByParentId($parentId);
    }

    public function langurl($url)
    {
	    if($this->isExternalUrl($url)){
		    return $url;
	    } else {
		    return "/" . \Yii::$app->language . $url;
	    }
    }

	public function isExternalUrl($url)
	{
		return (strpos($url, 'http') !== false);
	}

    public function setMapCallback($jsFunc)
    {
        $this->mapCallback = $jsFunc;
    }

    public function getCurrentPage()
    {
        return $this->context->currentPage;
    }

    public function getDivisions()
    {
        return Subdivision::getAll();
    }

    public function getDevisionsByParentId($parentId = null)
    {
        return Subdivision::getByParentId($parentId);
    }

    public function getSubdivisions()
    {
        return Subdivision::find()->where(['enabled'=>1, 'is_region'=>1])->orderBy(['ord'=>SORT_ASC])->all();
    }

    public function getMapData()
    {
        $aCoords = Subdivision::find()->where(['enabled'=>1])->andWhere(['not', ['lat' => null]])->all();
        $items = [];
        foreach ($aCoords as &$coord){
            $parent = $coord->getParentOrSelf();
            $root = $coord->getRootParentOrSelf();
            $items[] = [
                'title_en' => $coord->point_en,
                'title_ru' => $coord->point_ru,
                'lat' => $coord->lat,
                'lng' => $coord->lng,
                'parent' => $parent->title_en,
                'rootparent' => $root->title_en
            ];
        }
        return json_encode([
            "idMap" => "mapCompany",
            "oCenter" => [
                "lat" => 59.195522,
                "lng" => 63.101819
            ],
            "aCoords" => $items
        ]);
    }

    public function getErrorsText()
    {
        return Json::encode(User::getErrorsText());
    }
}