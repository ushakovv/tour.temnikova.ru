<?php

namespace core\components;


use app\components\ActiveRecord;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\helpers\Html;
use yii\imagine\Image;

class TreeViewInput extends \kartik\tree\TreeViewInput
{
    public function initOptions()
    {
        parent::initOptions();
    }

    public function renderHeading()
    {
        return "";
    }

    /**
     * Validation of source query data
     *
     * @throws InvalidConfigException
     */
    protected function validateSourceData()
    {
        if (empty($this->query) || !$this->query instanceof ActiveQuery) {
            throw new InvalidConfigException(
                "The 'query' property must be defined and must be an instance of '" . ActiveQuery::className() . "'."
            );
        }
        $class = isset($this->query->modelClass) ? $this->query->modelClass : null;
        if (empty($class) || !is_subclass_of($class, ActiveRecord::className())) {
            throw new InvalidConfigException("The 'query' must be implemented using 'ActiveRecord::find()' method.");
        }
    }

    /**
     * Renders the markup for the tree hierarchy - uses a fast non-recursive mode of tree traversal.
     *
     * @return string
     */
    public function renderTree()
    {

        $out = $this->_renderUl(null, 0);
        return Html::tag('div', $this->renderRoot() . $out, $this->treeOptions);
    }

    private function _renderUl($parentId, $nodeDepth)
    {
        $struct = $this->_module->treeStructure + $this->_module->dataStructure;
        extract($struct);
        $out = "";
        foreach ($this->_nodes as $node) {

            if($node->parent_id == $parentId){
                /**
                 * @var Tree $node
                 */
                /** @noinspection PhpUndefinedVariableInspection */
                $nodeKey = $node->$keyAttribute;
                /** @noinspection PhpUndefinedVariableInspection */
                $nodeName = $node->$nameAttribute;

                $nodeIcon = Html::tag('span', '', ['class' => 'fa fa-folder kv-node-closed'])
                    .Html::tag('span', '', ['class' => 'fa fa-folder-open kv-node-opened']);

                $nodeIconType = 2;

                $isChild = ($node->parent_id > 0);
                $indicators = '';
                $css = '';

                if (isset($this->nodeLabel)) {
                    $label = $this->nodeLabel;
                    $nodeName = is_callable($label) ? $label($node) :
                        (is_array($label) ? ArrayHelper::getValue($label, $nodeKey, $nodeName) : $nodeName);
                }
                if (trim($indicators) == null) {
                    $indicators = '&nbsp;';
                }
                $nodeOptions = [ 'data-key' => $nodeKey, 'data-lvl' => $nodeDepth ];
                if (!$isChild) {
                    $css = ' kv-parent ';
                }
                $indicators .= $this->renderToggleIconContainer(false) . "\n";
                $indicators .= $this->showCheckbox ? $this->renderCheckboxIconContainer(false) . "\n" : '';
                $css = trim($css);
                if (!empty($css)) {
                    Html::addCssClass($nodeOptions, $css);
                }
                $out .= Html::beginTag('li', $nodeOptions) . "\n" .
                    Html::beginTag('div', ['tabindex' => -1, 'class' => 'kv-tree-list']) . "\n" .
                    Html::beginTag('div', ['class' => 'kv-node-indicators']) . "\n" .
                    $indicators . "\n" .
                    '</div>' . "\n" .
                    Html::beginTag('div', ['tabindex' => -1, 'class' => 'kv-node-detail']) . "\n" .
                    $this->renderNodeIcon($nodeIcon, $nodeIconType, $isChild) . "\n" .
                    Html::tag('span', $nodeName, ['class' => 'kv-node-label']) . "\n" .
                    '</div>' . "\n" .
                    '</div>' . "\n".
                    $this->_renderUl($nodeKey, $nodeDepth + 1) .
                    '</li>';
            }
        }
        if($out){
            $out = Html::tag('ul', $out, ['class' => $nodeDepth ? '' : 'kv-tree']);
        }
        return $out;
    }


}