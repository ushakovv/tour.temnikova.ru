<?php

namespace core\components;

use app\components\CollectionService;
use app\models\Article;
use app\models\Product;
use core\media\gallery\GalleryManager;
use core\widgets\MultiList;
use core\widgets\Tabular;
use kartik\widgets\Select2;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use core\components\TreeViewInput;
use yii\helpers\Url;

class VirtualField extends \yii\bootstrap\ActiveField
{
    public $checkboxTemplate = "<div class=\"checkbox clip-check check-primary\">\n{beginLabel}\n{input}\n{labelTitle}\n{endLabel}\n{error}\n{hint}\n</div>";
    public $horizontalCheckboxTemplate = "{beginWrapper}\n<div class=\"checkbox clip-check check-primary\">\n{input}\n{beginLabel}\n{labelTitle}\n{endLabel}\n</div>\n{error}\n{endWrapper}\n{hint}";

    private $_pivotTable;
    private $_pivotFieldFrom;
    private $_pivotFieldTo;

    private function _initPivot($pivotTable, $pivotFieldFrom, $pivotFieldTo)
    {
        $this->_pivotTable = $pivotTable;
        $this->_pivotFieldFrom = $pivotFieldFrom;
        $this->_pivotFieldTo = $pivotFieldTo;
    }

    private function _getIds($query, $field = "id")
    {
        $queryClone = clone $query;
        $modelClass = $query->modelClass;
        $ids = [];
        if($this->model->id){
            $ids = $queryClone->select([ "`" . $modelClass::tableName() . "`.`" . $field ."`" ])
                ->innerJoin($this->_pivotTable, "`$this->_pivotTable`.`$this->_pivotFieldTo` = `" . $modelClass::tableName() . "`.`id` AND `$this->_pivotTable`.`$this->_pivotFieldFrom` = " . $this->model->id)
                ->column();
        }
        return $ids;
    }

    private function _registerManyToManyHandler()
    {
        UpdateScenario::registerHandler(
            $this->attribute,
            '\core\components\Many2ManyHelper',
            'updateLink',
            [
                'pivotTable' => $this->_pivotTable,
                'pivotFieldFrom' => $this->_pivotFieldFrom,
                'pivotFieldTo' => $this->_pivotFieldTo
            ],
            true
        );
    }


    /**
     * @param ActiveQuery $query
     * @param $pivotTable
     * @param $pivotFieldFrom
     * @param $pivotFieldTo
     * @return string
     */
    public function multiList($query, $pivotTable, $pivotFieldFrom, $pivotFieldTo)
    {
        $this->_initPivot($pivotTable, $pivotFieldFrom, $pivotFieldTo);
        $ids = $this->_getIds($query);
        
        $this->_registerManyToManyHandler();


        $items = [];
        if(!empty($ids)){
            $modelClass = $query->modelClass;
            $items = $modelClass::find()->where(['in', 'id', $ids])->all();
        }

        return $this->widget(MultiList::className(), [
            'items' => $items,
            'modelClass' => $this->model->className()
        ]);
    }

    /**
     * @param ActiveQuery $query
     * @param $pivotTable
     * @param $pivotFieldFrom
     * @param $pivotFieldTo
     * @return string
     */
    public function multiTreeDropdown($query, $pivotTable, $pivotFieldFrom, $pivotFieldTo)
    {
        $this->_initPivot($pivotTable, $pivotFieldFrom, $pivotFieldTo);
        $ids = $this->_getIds($query);
        $value = implode(",", $ids);

        $this->_registerManyToManyHandler();


        return $this->widget(TreeViewInput::className(), [
            // single query fetch to render the tree
            'query'             => $query,
            'isAdmin'           => true,
            'showInactive'      => true,
            //'headingOptions'    => ['label' => 'Categories'],
            'headerTemplate'    => "",
            'value'             => $value,         // values selected (comma separated for multiple select)
            'asDropdown'        => true,            // will render the tree input widget as a dropdown.
            'multiple'          => true,            // set to false if you do not need multiple selection
            'fontAwesome'       => true,            // render font awesome icons
            'rootOptions'       => [
                'label' => '<i class="fa fa-tree"></i>',
                'class'=>'text-success hide'
            ],                                      // custom root label
            //'options'         => ['disabled' => true],
        ]);
    }

    /**
     * @param ActiveQuery $query
     * @param $pivotTable
     * @param $pivotFieldFrom
     * @param $pivotFieldTo
     * @return string
     */
    public function multiListDropdown($query, $pivotTable, $pivotFieldFrom, $pivotFieldTo)
    {
        $this->_initPivot($pivotTable, $pivotFieldFrom, $pivotFieldTo);
        $ids = $this->_getIds($query);
        $value = implode(",", $ids);

        $this->_registerManyToManyHandler();

        return $this->widget(ListTreeViewInput::className(), [
            // single query fetch to render the tree
            'query'             => $query,
            'isAdmin'           => true,
            'showInactive'      => true,
            //'headingOptions'    => ['label' => 'Categories'],
            'headerTemplate'    => "",
            'value'             => $value,         // values selected (comma separated for multiple select)
            'asDropdown'        => true,            // will render the tree input widget as a dropdown.
            'multiple'          => true,            // set to false if you do not need multiple selection
            'fontAwesome'       => true,            // render font awesome icons
            'rootOptions'       => [
                'label' => '<i class="fa fa-tree"></i>',
                'class'=>'text-success hide'
            ],                                      // custom root label
            //'options'         => ['disabled' => true],
        ]);
    }

    public function multiSelectDropdownListByModel($className, $valueField = 'id', $titleField = 'name', $pivotTable, $pivotFieldFrom, $pivotFieldTo)
    {
        $fullClassName = ClassHelper::getClassPath($className);
        UpdateScenario::registerHandler(
            $this->attribute,
            '\core\components\Many2ManyHelper',
            'updateLink',
            [
                'pivotTable' => $pivotTable,
                'pivotFieldFrom' => $pivotFieldFrom,
                'pivotFieldTo' => $pivotFieldTo,
                'valueFrom' => $this->model->id
            ],
            true
        );

        if ($this->model->id) {
            $value = $fullClassName::find()->select(["`" . $fullClassName::tableName() . "`.`id`"])
                ->innerJoin($pivotTable, "`$pivotTable`.`$pivotFieldTo` = `" . $fullClassName::tableName() . "`.`id` AND `$pivotTable`.`$pivotFieldFrom` = " . $this->model->id)
                ->column();
        } else {
            $value = [];
        }


        return $this->widget(Select2::className(), [
            'attribute' => $this->attribute,
            'value' => $value,
            'view' => $this->form->getView(),
            'data' => ArrayHelper::map($fullClassName::find()->all(), $valueField, $titleField),
            'options' => [
                'placeholder' => 'Ничего не выбрано ...',
                'class' => 'form-control',
                'multiple' => true
            ],
        ]);
    }

    public function imageGallery($subtype = 'main', $options = [])
    {
        return $this->widget(GalleryManager::className(), [
            'model' => $this->model,
            'apiRoute' => Url::to('galleryApi'),
            'subtype' => $subtype
        ]);
    }

    /**
     * @param ActiveQuery $query
     * @param $pivotTable
     * @param $pivotFieldFrom
     * @param $pivotFieldTo
     * @return string
     */
    public function collection($modelClasses)
    {
        /*$this->_initPivot($pivotTable, $pivotFieldFrom, $pivotFieldTo);
        $ids = $this->_getIds($query);*/

        //$this->_registerManyToManyHandler();

        UpdateScenario::registerHandler(
            $this->attribute,
            '\core\components\Many2ManyHelper',
            'updateCollection',
            [
                'record_type' => $this->model->tableName(),
            ],
            true
        );

        return $this->widget(MultiList::className(), [
            'items' => CollectionService::getItemsByModel($this->model, null, true),
            'modelClass' => $modelClasses
        ]);
    }

    /**
     * @param ActiveQuery $tagsQuery
     * @param string $titleField
     * @param string $pivotTable
     * @param string $pivotFieldFrom
     * @param string $pivotFieldTo
     * @return $this
     */
    public function tagsByQuery($tagsQuery, $titleField = 'name', $pivotTable, $pivotFieldFrom, $pivotFieldTo, $options = [], $pluginOptions = [])
    {
        $this->_initPivot($pivotTable, $pivotFieldFrom, $pivotFieldTo);
        $fullClassName = $tagsQuery->modelClass;

        UpdateScenario::registerHandler(
            $this->attribute,
            '\core\components\Many2ManyHelper',
            'updateTags',
            [
                'pivotTable' => $pivotTable,
                'pivotFieldFrom' => $pivotFieldFrom,
                'pivotFieldTo' => $pivotFieldTo,
                'valueFrom' => $this->model->id,
                'tagModel' => $fullClassName
            ],
            true
        );

        $items = $this->_getIds($tagsQuery, "name");

        return $this->widget( Select2::className(), [
            'value' => $items,
            'data' => ArrayHelper::map($tagsQuery->all(), $titleField, $titleField),
            'options' => ArrayHelper::merge([ 'placeholder' => 'Ничего не выбрано ...', 'multiple' => true ], $options),
            'pluginOptions' => ArrayHelper::merge( [ 'tags' =>true, 'maximumInputLength' => 100 ], $pluginOptions)
        ]);
    }

    public function tabular($relation, $template)
    {
        return $this->widget(Tabular::className(), [
            "form" => $this->form,
            "model" => $this->model,
            "relation" => $relation,
            "template" => $template
        ]);
    }

    public function widget($class, $config = [])
    {
        /* @var $class \yii\base\Widget */
        $config['view'] = $this->form->getView();
        if(!isset($config['name'])){
            $config['name'] = Html::getInputName($this->model, $this->attribute);
        }
        $this->parts['{input}'] = $class::widget($config);

        return $this;
    }
}