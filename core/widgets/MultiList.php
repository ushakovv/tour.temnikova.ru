<?php

namespace core\widgets;

use core\components\CollectionItemInterface;
use yii\base\Widget;
use yii\db\ActiveRecord;

class MultiList extends Widget
{
    public $items;

    public $name;

    public $modelClass;
    
    public function run()
    {
         echo $this->render('multiList', [
             'items' => $this->items,
             'name' => $this->name,
             'modelClass' => $this->modelClass
         ]);
    }

    public static function renderListItemInner($item)
    {
        if($item instanceof CollectionItemInterface){
            $badge = "<a href='". $item->adminUrl . "' target='_blank'><span class=\"label label-info\">" . $item->getTypeName() . "</span></a> ";
        }
        return $badge . $item->name;
    }

    /**
     * @param ActiveRecord $item
     */
    public static function renderListItem($item, $name, $isMultiModel)
    {
        $id = $isMultiModel ? ($item->className() . ":" . $item->id) : $item->id;
        return "<div class='multilist-item'>" .
        "<input type='hidden' name='${name}[]' value='" . $id . "'>" .
        self::renderListItemInner($item) .
        "<span class=\"glyphicon glyphicon-remove pull-right\" aria-hidden=\"true\" style='cursor:pointer' onclick='$(this.parentNode).remove()'></span>" .
        "</div>";
    }

}