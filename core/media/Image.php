<?php

namespace core\media;

class Image
{
    private $_type;
    private $_value;
    /**
     * Image constructor.
     * @param string $type
     * @param int $value
     */
    public function __construct($type, $value = null)
    {
        $this->_type = $type;
        $this->_value = $value;
    }

    /**
     * @param string|null $variant
     * @return string
     */
    public function getUrl($variant = null)
    {
        if($this->_value){
            if($variant){
                return \Yii::$app->imageManager->getUrl($this->_type, $variant, $this->_value);
            } else {
                return \Yii::$app->imageManager->getUrlOriginal($this->_type, $this->_value);
            }
        } else {
            return null;
        }
    }

    function __get($name)
    {
        return $this->getUrl($name);
    }


    function __toString()
    {
        return $this->getUrl();
    }
}