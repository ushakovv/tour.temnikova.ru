<?php

namespace core\components;


use yii\helpers\Html;

class ListTreeViewInput extends TreeViewInput
{
    public function renderTree()
    {
        $struct = $this->_module->treeStructure + $this->_module->dataStructure;
        extract($struct);
        $out = "";
        foreach ($this->_nodes as $node) {
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
                $nodeOptions = [ 'data-key' => $nodeKey, 'data-lvl' => 0 ];
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
                    $this->renderNodeIcon($nodeIcon, $nodeIconType, false) . "\n" .
                    Html::tag('span', $nodeName, ['class' => 'kv-node-label']) . "\n" .
                    '</div>' . "\n" .
                    '</div>' . "\n".
                    '</li>';

        }
        if($out){
            $out = Html::tag('ul', $out, ['class' => 'kv-tree']);
        }
        return Html::tag('div', $this->renderRoot() . $out, $this->treeOptions);
    }

}