<?php

namespace core\media;

use core\components\UpdateScenario;

class FileWidget extends \yii\widgets\InputWidget
{

    public $validator = 'file';
    /**
     * @see http://www.yiiframework.com/doc-2.0/guide-tutorial-core-validators.html#file
     */
    public $validatorOptions = [];

    private function _isImage($fileInfo)
    {
        return in_array(strtolower($fileInfo['extension']), ['jpg', 'jpeg', 'png', 'gif']);
    }

    public function run()
    {
        $value = $this->model->{$this->attribute};
        $fileInfo = null;
        if ($value) {
            $fileInfo = [
                'path' => $value,
                'url' => \Yii::$app->fileManager->getUrl($value),
                'extension' => pathinfo($value, PATHINFO_EXTENSION)
            ];
            $fs = \Yii::$app->fileManager->getFs();
            if (file_exists(\Yii::getAlias('@webroot' . $value))) {
                $fileInfo['size'] = filesize(\Yii::getAlias('@webroot' . $value));
                $fileInfo['showPreview'] = $this->_isImage($fileInfo);
                if ($fileInfo['showPreview']) {
                    try {
                        $geometry = \Yii::$app->imageProcessor->create(file_get_contents(\Yii::getAlias('@webroot' . $value)))->getImageGeometry();
                        $fileInfo['imageGeometry'] = $geometry['width'] . "x" . $geometry['height'];
                    } catch (\Exception $e) {
                        unset($fileInfo['showPreview']);
                        $fileInfo['error'] = 'Corrupted image';
                    }
                }
            } else {
                $fileInfo['error'] = 'Missing file';
            }
            $fileInfo['showDelete'] = !$this->model->isAttributeRequired($this->attribute);
        }

        UpdateScenario::registerHandler($this->attribute, '\core\media\FileManager', 'processPostField', [
            'validator' => $this->validator,
            'validatorOptions' => $this->validatorOptions
        ]);

        echo $this->render('fileWidget', [
            'fileInfo' => $fileInfo
        ]);
    }

} 