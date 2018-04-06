<?php

namespace core\ckeditor;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;
use yii\helpers\Json;
use yii\widgets\InputWidget;
class CKEditor extends InputWidget
{
    /**
     * Editor options
     * @var array
     */
    public $editorOptions = [];
    /**
     * Container options
     * @var array
     */
    public $containerOptions = [];
    /**
     * Add extra plugins
     * @var array
     */
    public $extraPlugins = [
        'codemirror',
    ];
    private $corePlugins = [];
    /**
     * Initialisation on event
     * @var bool
     */
    public $initOnEvent = false;
    private $_inline = false;
    /**
     * @var string
     */
    public $preset = CKEditorPresets::STANDART;
    const TYPE_STANDARD = 'standard';
    const TYPE_INLINE = 'inline';
    public function init()
    {
        parent::init();
        if (array_key_exists('inline', $this->editorOptions)) {
            $this->_inline = $this->editorOptions['inline'];
            unset($this->editorOptions['inline']);
        }
        if (array_key_exists('preset', $this->editorOptions)) {
            if ($this->editorOptions['preset']) {
                $this->getPreset($this->editorOptions['preset']);
            }
            unset($this->editorOptions['preset']);
        }
        if ($this->_inline && !isset($this->editorOptions['height']))
            $this->editorOptions['height'] = 100;
        if ($this->_inline && !isset($this->containerOptions['id']))
            $this->containerOptions['id'] = $this->id . '_inline';
    }
    /**
     * @param $preset
     */
    private function getPreset($preset)
    {
        $options = CKEditorPresets::getPresets($preset);
        $options['extraPlugins'] = $this->plugins();
        if ($this->_inline) {
            $options['extraPlugins'] = 'sourcedialog';
            $options['removePlugins'] = 'sourcearea';
        }
        $this->editorOptions = ArrayHelper::merge($options, $this->editorOptions);
    }
    public function run()
    {
        Assets::register($this->getView());
        $this->addExtraPlugins();
        echo Html::beginTag('div', $this->containerOptions);
        if ($this->hasModel()) {
            echo Html::activeTextarea($this->model, $this->attribute, $this->options);
        } else {
            echo Html::textarea($this->name, $this->value, $this->options);
        }
        echo Html::endTag('div');
        if ($this->_inline) {
            $editorJs = $this->getCKeditor(self::TYPE_INLINE);
            $this->getView()->registerCss('#' . $this->containerOptions['id'] . ', #' . $this->containerOptions['id']
                . ' .cke_textarea_inline{height: ' . $this->editorOptions['height'] . 'px;}');
            $this->getView()->registerJs($editorJs, View::POS_END);
        } elseif ($this->initOnEvent) {
            $editorJs = $this->getCKeditor(self::TYPE_STANDARD);
            $js = 'jQuery("#' . $this->options['id'] . '").one("' . $this->initOnEvent . '", function () {' . $editorJs . '});';
            $this->getView()->registerJs($js, View::POS_END);
        } else {
            $editorJs = $this->getCKeditor(self::TYPE_STANDARD);
            $this->getView()->registerJs($editorJs, View::POS_END);
        }
    }
    private function getCKeditor($type)
    {
        $editorJs = null;
        switch ($type) {
            case self::TYPE_STANDARD :
                $editorJs = $this->typeStandard();
                break;
            case self::TYPE_INLINE :
                $editorJs = $this->typeInline();
                break;
        }
        return $editorJs;
    }
    private function typeInline()
    {
        $js = "CKEDITOR.inline(";
        $js .= Json::encode($this->options['id']);
        $js .= empty($this->editorOptions) ? '' : ', ' . Json::encode($this->editorOptions);
        $js .= ");";
        return $js;
    }
    private function typeStandard()
    {
        $js = "CKEDITOR.replace(";
        $js .= Json::encode($this->options['id']);
        $js .= empty($this->editorOptions) ? '' : ', ' . Json::encode($this->editorOptions);
        $js .= ");";
        return $js;
    }
    private function addExtraPlugins()
    {
        if (!is_array($this->extraPlugins) || !count($this->extraPlugins)) {
            return false;
        }
        $bundle = AssetsPlugins::register($this->getView());
        foreach ($this->extraPlugins as $name) {
            $pluginJs = "CKEDITOR.plugins.addExternal('$name', '$bundle->baseUrl/$name/');";
            $this->getView()->registerJs($pluginJs, View::POS_END);
        }
        return true;
    }
    private function plugins()
    {
        $plugins = ArrayHelper::merge($this->corePlugins, $this->extraPlugins);
        return implode(',', $plugins);
    }
} 