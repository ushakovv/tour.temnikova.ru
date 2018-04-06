<?= \core\components\AdminHtml::header("Change password for " . $model->username); ?>

<div class="container-fluid container-fullw bg-white">
<form id="w0" class="form-horizontal" action="/m6GaVsjn/password/index" method="post" enctype="multipart/form-data">
    <input type="hidden" name="_csrf" value="<?= \Yii::$app->request->getCsrfToken() ?>"/>
    <div class="control-group">
        <label class="control-label">Old password</label>
        <div class="controls">
            <p style="margin-top: 5px;">
                <input type="password" name="oldPassword" placeholder="" class="form-control">
            </p>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label">New password</label>
        <div class="controls">
            <p style="margin-top: 5px;">
                <input type="password" name="password" placeholder="" class="form-control">
            </p>
        </div>
    </div>
    <?php
    if ($error != '') {
        echo '<div class="alert alert-danger">' . $error . '</div>';
    }
    if ($success != '') {
        echo '<div class="alert alert-success">' . $success . '</div>';
    }
    ?>


    <div>
        <button type="submit" class="btn btn-green">Submit new password</button>
    </div>

</form>
</div>