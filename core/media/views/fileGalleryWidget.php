<style>
	.media-file-delete {
		cursor: pointer;
	}
	.media-file-delete:hover{
		background-color: #d9534f;
	}
	.media-files-container {
		overflow: auto;
		height: 292px;
	}
</style>
<div class="form-group">
	<label class="control-label col-sm-3" for="portfolio-tags"><?= $this->context->title ?></label>
	<div class="col-sm-6">
		<?php

			echo \kartik\widgets\FileInput::widget([
				'name' => $this->context->name,
				'options' => [
					'multiple' => true
				],
				'pluginOptions' => [
					'showUpload' => false
				]
			]);

			$class = count($files) > 5 ? "media-files-container" : "";

			echo "<div class='$class'>";

			foreach($files as $fileInfo){
				$deleteUrl =  \yii\helpers\Url::toRoute( \Yii::$app->controller->id . "/delete-file/?file_id=" . $fileInfo['id'] . '&' );
				echo "<div class='mediaFile {$fileInfo['extension']}'> <a href='{$fileInfo['url']}' target='_blank'>{$fileInfo['path']}</a> <span class='label label-default media-file-delete' onclick='MediaFile.del(this, \"". $deleteUrl . "\")'>удалить</span>";
				if(isset($fileInfo['size'])){
					echo "<br>" . \Yii::$app->formatter->asSize($fileInfo['size']);
				}
				echo "</div>";
			}

			echo "</div>";

		?>
	</div>
</div>
