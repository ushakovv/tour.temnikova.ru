<?php

echo \kartik\widgets\FileInput::widget([
    'model' => $this->context->model,
    'attribute' => $this->context->attribute,
    'options' => [
        'multiple' => false
    ],
    'pluginOptions' => [
        //'previewFileType' => 'any'
        'showUpload' => false
    ]
]);

if ($fileInfo) {
    $deleteUrl = \yii\helpers\Url::toRoute(\Yii::$app->controller->id . "/delete-file/?id=" . $this->context->model->id . '&attribute=' . $this->context->attribute . '&');

    if (isset($fileInfo['showPreview']) && $fileInfo['showPreview']) {
        echo "<div class='js-mediaFile mediaFileImage'><image src='{$fileInfo['url']}'>";
    } else {
        echo "<div class='js-mediaFile mediaFile {$fileInfo['extension']}'> ";
    }


    echo "<a href='{$fileInfo['url']}' target='_blank'>{$fileInfo['path']}</a>";
    //<span class='label label-default media-file-delete' onclick='MediaFile.del(this, \"". $deleteUrl . "\")'>удалить</span>";
    if($fileInfo['showDelete']) {
        ?>
        <a class="btn redOnHover"
           onclick="return MediaFile.del(this, '/<?= \yii\helpers\Url::to(\Yii::$app->controller->uniqueId . "/update-attribute") ?>', '<?= $this->context->attribute ?>', <?= $this->context->model->id ?>)"><span
                class="glyphicon glyphicon-remove" aria-hidden="true"></span> Удалить</a>

        <?php
    }
    if (isset($fileInfo['error'])) {
        echo "<div class='mediaFileError'><i class=\"glyphicon glyphicon-warning-sign\"></i> " . $fileInfo['error'] . "</div>";
    } else {
        if (isset($fileInfo['size'])) {
            echo "<br>" . \Yii::$app->formatter->asSize($fileInfo['size']);
        }
        if (isset($fileInfo['imageGeometry'])) {
            echo ", " . $fileInfo['imageGeometry'];
        }
    }

    echo "</div>";
}
