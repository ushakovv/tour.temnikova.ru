<?php

use yii\helpers\Html;

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

//echo Html::fileInput(Html::getInputName($this->context->model, $this->context->attribute), null);
$attr = $this->context->attribute;
$modalId = $this->context->attribute . "-modal";
?>
<div class="mediaImageInfo">
    <div id="image-<?= $attr ?>-variants">
        <?=  \core\media\ImageWidget::renderVariants($this->context->getImageType(), $value, $attribute, $model_id); ?>
    </div>
    <?php
        if($value) {
            ?>
            <a class="btn" style="color: red; margin-top: 16px"
               onclick="return MediaImage.remove(this, '/<?= \yii\helpers\Url::to(\Yii::$app->controller->uniqueId . "/update-attribute") ?>', '<?= $this->context->attribute ?>', <?= $this->context->model->id ?>)"><span
                    class="glyphicon glyphicon-remove" aria-hidden="true"></span> Очистить</a>
            <?php
        }
            ?>
</div>
<style>
    .mediaimage__block {
        float: left;
    }

    .mediaimage__info {
        margin: 10px 0 0 10px;
        position: absolute;
        background-color: black;
        color: white;
        font-size: 12px;
        opacity: 0.5;
    }

    .mediaimage__edit {
        margin: 70px 0 0 10px;
        position: absolute;
        background-color: black;
        color: white;
        font-size: 12px;
        opacity: 0.5;
    }
</style>



<!--<button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">
    Launch demo modal
</button>-->

<!-- Modal -->
<div class="modal fade" id="<?= $this->context->attribute ?>-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Modal title</h4>
            </div>
            <div class="modal-body">
                <img src="" style="max-width: 100%"/><br>
                <div style="margin-top: 15px" class="js-file-container"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                <button type="button" class="btn btn-primary js-modal-upload-file" style="display: none" onclick="return ModalPreviewUploader.upload(this)">Заменить</button>
            </div>
        </div>
    </div>
</div>

<?php
$js = <<<JS
    $("body").on("click", ".mediaimage__block a", function () {
        var data = $(this).data();
        var jModal = $("#" + data.modal);
        jModal.find('.modal-title').html("Картинка " + data.variant);
        jModal.find('.modal-body img').attr('src', data.url);
        jModal.find(".js-modal-upload-file").hide();
        jModal.find(".js-file-container").html('<input type="file" name="file" onchange="ModalPreviewUploader.onchange(this)">');
        jModal.find('form').trigger('reset');
        $("#" + data.modal).modal();
        ModalPreviewUploader.currentData = data;
        ModalPreviewUploader.currentPopup = jModal;
        return false;
    });
    ModalPreviewUploader = {
        currentPopup: null,
        currentData: null,
        onchange: function(obj){
            //$(".js-modal-upload-file").show();
            ModalPreviewUploader.upload(obj);
        },
        upload: function(fileObj){
            var log = console.log;
            //var file = $(buttonObj).parents(".modal-content").find("input")[0].files[0];
            var file = fileObj.files[0];
            
            var xhr = new XMLHttpRequest();

            // обработчик для закачки
            xhr.upload.onprogress = function(event) {
                log(event.loaded + ' / ' + event.total);
            }
        
            // обработчики успеха и ошибки
            // если status == 200, то это успех, иначе ошибка
            xhr.onload = xhr.onerror = function() {
                if (this.status == 200) {
                    $("#image-" + ModalPreviewUploader.currentData.attribute + "-variants").html( this.responseText );
                    ModalPreviewUploader.currentPopup.modal('hide');
                } else {
                    log("error " + this.status);
                }
            };
            
            var formData = new FormData();
            formData.append('_csrf', $("meta[name=csrf-token]").attr('content'));  
            formData.append('file', file);
        
            xhr.open("POST", "upload-variant?" + $.param( ModalPreviewUploader.currentData ), true);
            xhr.send(formData);
            
            return false;
        }
    }
JS;
$this->registerJs($js);