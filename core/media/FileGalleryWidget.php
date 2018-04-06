<?php

namespace core\media;

use core\components\UpdateScenario;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\base\Widget;
use yii\db\Query;
use yii\helpers\Html;

class FileGalleryWidget extends Widget {

	public $model;

	public $name;

	public $subtype;

	public $title;

	public $options = [];

	public function run()
	{
		$type = strtolower( $this->model->formName() );
		UpdateScenario::registerHandler("media_files_" . $this->subtype, '\core\media\FileManager', 'processPostField', [
			'type' => $type,
			'subtype' => $this->subtype,
			'id' => $this->model->id,
			'options' => $this->options
		], true);

		$fileRows = (new Query())
			->from('media_files')
			->where(['object_type' => $type, 'object_id' => $this->model->id, 'object_subtype' => $this->subtype])
			->all();

		$files = [];
		foreach($fileRows as $row){
			$filename = $row['file'];
			$fileInfo = [
				'id' => $row['id'],
				'path' => $filename,
				'url' => \Yii::$app->fileManager->getUrl($filename),
				'extension' => pathinfo($filename, PATHINFO_EXTENSION)
			];
			$transport = \Yii::$app->fileManager->getTransport();
			/**
			 * @var \core\media\transport\LocalTransport $transport
			 */
			if($transport->exists($filename)){
				$fileInfo['size'] = $transport->filesize($filename);
			}
			$files[] = $fileInfo;
		}

		echo $this->render('fileGalleryWidget', [
			'files' => $files
		]);
	}

	/**
	 * Initializes the widget.
	 * If you override this method, make sure you call the parent implementation first.
	 */
	public function init()
	{
		if ($this->name === null && !$this->hasModel()) {
			throw new InvalidConfigException("Either 'name', or 'model' properties must be specified.");
		}
		if (!$this->name) {
			$this->name = $this->hasModel() ? Html::getInputName($this->model, "media_files_" . $this->subtype . "[]") : $this->getId();
		}
		parent::init();
	}

	/**
	 * @return boolean whether this widget is associated with a data model.
	 */
	protected function hasModel()
	{
		return $this->model instanceof Model;
	}

} 