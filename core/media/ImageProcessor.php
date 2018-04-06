<?php

/**
 * Image processor master class.
 *
 * Add this to config for ImageMagick image processor:
 * <pre>
 * 'components' => array(
 *    'imageProcessor' => array(
 *       'class' => 'core.components.utils.images.PImageProcessorImagick'
 *    )
 * )
 * </pre>
 *
 * Usage example:
 * <pre>
 * Yii::app()->imageProcessor->load($fromFile)->thumbnail(300, 200)->setQuality(70)->save($toFile);
 * </pre>
 *
 * @author Dmitry Latikov <dlatikov@promo.ru>
 * @package core.components.utils.images
 * @see IImageProcessor, PImageActiveRecord
 *
 * @property string $quality Image quality
 */

namespace core\media;

use creocoder\flysystem\LocalFilesystem;

abstract class ImageProcessor extends \yii\base\Component
{
    /**
     * @var int Quality for jpeg image
     */
    protected $_quality = 90;

    protected $_transport = null;

    protected $_extension = 'jpg';

    protected $bgColor = [255, 255, 255];

    /**
     * @var mixed Image processing object, e.g. Imagick object
     */
    protected $_imageHander = NULL;

    /**
     * @return LocalFilesystem
     */
    protected function getFs()
    {
        if (!$this->_transport) {
            $this->_transport = \Yii::$app->get('fs', false);
        }
        if (!$this->_transport) {
            $this->_transport = new LocalFilesystem();
        }
        return $this->_transport;
    }

    /**
     * @param string $file
     * @return ImageProcessorGd
     * @throws ImageProcessorException
     */
    public function load($file)
    {
        try {
            $content = $this->getFs()->read($file);
            if (!$content) {
                throw new ImageProcessorException();
            }
            return $this->create($content);
        } catch (\Exception $e) {
            throw new ImageProcessorException('Image load error: ' . $file);
        }
    }

    public function save($file)
    {
        $content = $this->getContent();
        $this->getFs()->put($file, $content);
    }


    /**
     * Load image from source file, apply array of commands to it and save result to target file.
     *
     * E.g.:
     * <pre>
     * Yii::app()->imageProcessor->process(
     *    $fromFile,
     *    $toFile,
     *    array(
     *         "thumbnail" => array("width" => 100, "height" => 100),
     *       "crop" => array("width" => 50, "height" => 50, "x" => 10, "y" => 10)
     *    )
     * );
     * </pre>
     *
     * @param string $sourceFile
     * @param string $targetFile
     * @param array $commandArray
     * @throws ImageProcessorException
     */
    public function process($sourceFile, $targetFile, $commandArray)
    {
        if($sourceFile){
            $this->load($sourceFile);
        }

        $this->_extension = 'jpg';

        // set properties
        foreach ($commandArray as $command => $params) {
            if ($command && !is_array($params)) {
                $this->executeCommand($command, $params);
            }
        }
        // execute commands
        foreach ($commandArray as $command => $params) {
            if ($command && is_array($params)) {
                $this->executeCommand($command, $params);
            }
        }

        $this->save($targetFile);
    }

    /**
     * Execute processor method with given params.
     *
     * @param string $command Method name, e.g. 'scaleDown'.
     * @param array $params Array of method params, e.g. array('width' => 400, 'height' => 300).
     */
    public function executeCommand($command, $params = array())
    {
        // _comand syntax for same command to use multiple times
        $command = ltrim($command, '_');

        if (!is_array($params)) {
            // property assign
            $this->$command = $params;
        } else {
            // method execute
            $classReflection = new \ReflectionClass($this);

            if (!$classReflection->hasMethod($command)) {
                throw new \yii\base\Exception("Image processor method not found: " . $command);
            }

            if (is_array($params)) {
                $methodReflection = $classReflection->getMethod($command);
                $methodParams = $methodReflection->getParameters();

                $callArgs = array();

                if (empty($methodParams)) {
                    $callArgs = $params;
                } else {
                    foreach ($methodParams as $methodParam) {
                        if (isset($params[$methodParam->getName()])) {
                            $callArgs[] = $params[$methodParam->getName()];
                        } else {
                            if ($methodParam->isOptional()) {
                                $callArgs[] = $methodParam->getDefaultValue();
                            } else {
                                throw new \yii\base\Exception(\Yii::t('app', 'Image processor method "{method}" required parameter "{param}" missing', array('{method}' => $command, '{param}' => $methodParam->getName())));
                            }
                        }
                    }
                }

                $methodReflection->invokeArgs($this, $callArgs);
            } else {
                // setter for processor property
                $this->$command = $params;
            }
        }

    }

    /**
     * Scale image to fit dimensions, if image sizes are over them.
     *
     * @param int $width
     * @param int $height
     * @return PImageProcessor
     */
    public function thumbnail($width, $height)
    {
        $originalSizes = $this->getImageGeometry();

        if (
            $originalSizes['width'] * $height / $originalSizes['height'] >= $width
        ) {
            $this->resize(null, $height);
        } else {
            $this->resize($width, null);
        }

        $scaledSizes = $this->getImageGeometry();

        $this->crop(
            $width,
            $height,
            floor(($scaledSizes['width'] - $width) / 2),
            floor(($scaledSizes['height'] - $height) / 2)
        );

        return $this;
    }

    /**
     * Set output compression quality.
     *
     * @param $quality
     */
    public function setQuality($quality)
    {
        $this->_quality = $quality;
    }

    /**
     * Set extension
     *
     * @param $quality
     */
    public function setExtension($extension)
    {
        $this->_extension = $extension;
    }

    /**
     * Backward compatibility "process" alias.
     *
     * @param string $sourceFile
     * @param string $targetFile
     * @param array $commandArray
     * @deprecated Used for backward compatibility with v3.0 only. Use thumbnail method instead.
     */
    public function writeImageCropedThumbnail($sourceFile, $targetFile, $commandArray)
    {
        $this->process($sourceFile, $targetFile, $commandArray);
    }

}

class ImageProcessorException extends \yii\base\Exception
{

}

?>