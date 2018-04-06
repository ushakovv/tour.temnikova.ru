<?php

namespace core\components;

use admin\models\AdminSectionGroup;
use yii\helpers\Html;
use yii\helpers\Url;


class AdminHtml
{

    private static function _renderMenuHtml($nodes, $level){
        $html = "";
        $containsActive = false;
        foreach ($nodes as $node){
            $href = isset($node['url']) ? Url::toRoute($node['url'] . "/") : "javascript:void(0)";
            $active = isset($node['url']) && \Yii::$app->controller->id == $node['url'];
            $activeClass = $active ? 'active' : '';
            if(isset($node['items'])){
                list($nodeItemsHtml, $active) = self::_renderMenuHtml($node['items'], $level + 1);
                if($active){
                    $activeClass = 'active open';
                }
            }
            if($level == 1){
                $nodeHtml = '<a href="' . $href. '">'
                    .'<div class="item-content"><div class="item-media"><i class="ti-folder"></i></div>'
                    .'<div class="item-inner"><span class="title"> ' . $node['label'] . '</span><i class="icon-arrow"></i></div>'
                    .'</div>'
                    .'</a>';
            } else {
                $nodeHtml = '<a href="' . $href . '"> <span>' . $node['label'] . '</span> </a>';
            }
            if(isset($node['items'])){
                $nodeHtml .= '<ul class="sub-menu">' . $nodeItemsHtml . '</ul>';
            }
            $containsActive = $containsActive || $active;
            $html .= "<li class='$activeClass'>$nodeHtml</li>";
        }
        return [$html, $containsActive];
    }

    public static function menuFromConfig(){
        $menu = require(\Yii::getAlias('@admin') . "/config/menu.php");
        list($html, $active) = self::_renderMenuHtml($menu, 1);

        return "<ul class=\"main-navigation-menu\">" . $html . "</ul>";
    }

    public static function menuFromDb(){
        $user = \Yii::$app->user->getIdentity();
        $data = AdminSectionGroup::getCmsStructure($user->role_id, ['r' => 1]);
        $leftMenuItems = [];
        $i = 0;
        foreach($data as $group) {
            $leftMenuItems[$i] = [
                'label' => $group['name'],
                'items' => []
            ];
            foreach($group['sections'] as $section) {
                $leftMenuItems[$i]['items'][] = [
                    'label' => $section['name'],
                    'url' => $section['uri'],
                    'active' => (\Yii::$app->controller->id == $section['uri'])
                ];
            }
            $i++;
        }

        list($html, $active) = self::_renderMenuHtml($leftMenuItems, 1);

        return "<ul class=\"main-navigation-menu\">" . $html . "</ul>";
    }

    public static function header($title, $withParent = false)
    {
        $h1Content = Html::encode($title);
        if($withParent){
            $h1Content = '<a href="' . Url::toRoute(\Yii::$app->controller->id . '/index') . '">' . \Yii::$app->controller->sectionTitle . '</a> / '
                . $h1Content;
        }
        return '<section id="page-title" class="padding-top-15 padding-bottom-15">' .
            '<div class="row">' .
            '<div class="col-sm-8">' .
            '<h1 class="mainTitle">' . $h1Content  . '</h1>' .
            '</div>' .
            '</div>' .
            '</section>';
    }

    public static function button($title, $url, $icon = null)
    {
        $iconHtml = $icon ? "<i class=\"fa fa-$icon\"></i> " : "";
        return Html::a($iconHtml . $title, $url, ['class' => 'btn btn-green add-row']);
    }
}