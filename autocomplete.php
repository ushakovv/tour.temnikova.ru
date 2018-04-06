<?php
/*
 * Yii2 Autocomplete Helper
 * https://github.com/iiifx-production/yii2-autocomplete-helper
 *
 * Vitaliy IIIFX Khomenko (c) 2016
 */

class Yii extends \yii\BaseYii
{
    /**
     * @var BaseApplication|WebApplication|ConsoleApplication
     */
    public static $app;
}

/**
 * @property core\components\Formatter $formatter
 * @property yii\caching\FileCache $cache
 * @property app\components\Mailer $mailer
 * @property yii\db\Connection $db
 * @property core\media\ImageProcessorGd $imageProcessor
 * @property core\media\ImageManager $imageManager
 * @property creocoder\flysystem\LocalFilesystem $fs
 * @property core\media\FileManager $fileManager
 * @property app\components\View $view
 */
abstract class BaseApplication extends \yii\base\Application {}

/**
 * @property core\components\Formatter $formatter
 * @property yii\caching\FileCache $cache
 * @property app\components\Mailer $mailer
 * @property yii\db\Connection $db
 * @property core\media\ImageProcessorGd $imageProcessor
 * @property core\media\ImageManager $imageManager
 * @property creocoder\flysystem\LocalFilesystem $fs
 * @property core\media\FileManager $fileManager
 * @property app\components\View $view
 */
class WebApplication extends \yii\web\Application {}

/**
 * @property core\components\Formatter $formatter
 * @property yii\caching\FileCache $cache
 * @property app\components\Mailer $mailer
 * @property yii\db\Connection $db
 * @property core\media\ImageProcessorGd $imageProcessor
 * @property core\media\ImageManager $imageManager
 * @property creocoder\flysystem\LocalFilesystem $fs
 * @property core\media\FileManager $fileManager
 * @property app\components\View $view
 */
class ConsoleApplication extends \yii\console\Application {}
