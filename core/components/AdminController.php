<?php

namespace core\components;

use core\media\gallery\GalleryManagerAction;
use core\media\ImageWidget;
use core\widgets\MultiList;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use core\components\RoleService;
use yii\web\HttpException;
use yii\web\UploadedFile;

class AdminController extends Controller
{
    public $modelClass = null;
    public $searchModelClass = null;

    public $sectionTitle = "[Section title]";

    public function getUser()
    {
        if (!\Yii::$app->user->isGuest) {
            return \Yii::$app->user->identity;
        }
    }

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'galleryApi' => [
                'class' => GalleryManagerAction::className(),
            ],
        ];
    }

    /**
     * Lists all models.
     * @return mixed
     */
    public function actionIndex()
    {
        $user = $this->getUser();
        $crud_permissions = RoleService::checkUserPermission($user->role_id, $_SERVER['REQUEST_URI'], [], true);
        if (!$crud_permissions['r']) {
            throw new HttpException(403, 'Forbidden');
        }

        $searchModel = new $this->searchModelClass;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'crud_permissions' => $crud_permissions,
        ]);
    }

    /**
     * Displays a single model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $user = $this->getUser();
        if (!RoleService::checkUserPermission($user->role_id, $_SERVER['REQUEST_URI'], ['r' => 1])) {
            throw new HttpException(403, 'Forbidden');
        }
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $user = $this->getUser();
        if (!RoleService::checkUserPermission($user->role_id, $_SERVER['REQUEST_URI'], ['c' => 1])) {
            throw new HttpException(403, 'Forbidden');
        }

        $model = new $this->modelClass;

        $postData = Yii::$app->request->post();

        $attributes = isset($postData[$model->formName()]) ? $postData[$model->formName()] : null;

        if ($attributes) {
            $attributes = UpdateScenario::executeFromPost($model, $attributes, 'updateScenario', false);
            $this->update($model, $attributes);
        }

        if (!$model->hasErrors() && $attributes && $model->save()) {
            $attributes = UpdateScenario::executeFromPost($model, $attributes, 'updateScenario', true);
            if ($model->hasMethod('afterUpdateScenario')) {
                $model->afterUpdateScenario();
            }
            return $this->redirect(['update', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $user = $this->getUser();
        if (!RoleService::checkUserPermission($user->role_id, $_SERVER['REQUEST_URI'], ['u' => 1])) {
            throw new HttpException(403, 'Forbidden');
        }

        $model = $this->findModel($id);

        $postData = Yii::$app->request->post();

        $attributes = isset($postData[$model->formName()]) ? $postData[$model->formName()] : null;

        if ($attributes) {
            $attributes = UpdateScenario::executeFromPost($model, $attributes, 'updateScenario', false);
            $this->update($model, $attributes);
        }

        if (!$model->hasErrors() && $attributes && $model->save()) {
            $attributes = UpdateScenario::executeFromPost($model, $attributes, 'updateScenario', true);
            if ($model->hasMethod('afterUpdateScenario')) {
                $model->afterUpdateScenario();
            }
            return $this->redirect(['update', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function update($model, $attributes)
    {
        $model->attributes = $attributes;
    }

    /**
     * Deletes an existing model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $user = $this->getUser();
        if (!RoleService::checkUserPermission($user->role_id, $_SERVER['REQUEST_URI'], ['d' => 1])) {
            throw new HttpException(403, 'Forbidden');
        }

        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ActiveRecord the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $modelClass = $this->modelClass;
        if (($model = $modelClass::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionUpdateAttribute()
    {
        $user = $this->getUser();
        if (!RoleService::checkUserPermission($user->role_id, $_SERVER['REQUEST_URI'], ['u' => 1])) {
            throw new HttpException(403, 'Forbidden');
        }

        $request = \Yii::$app->request;
        $attribute = $request->post('attribute');
        $id = $request->post('id');
        $value = $request->post('value');

        $modelClass = $this->modelClass;
        $row = $modelClass::findOne($id);
        if ($row && $row->$attribute != $value) {
            $row->$attribute = $value;
            $row->save();
        }
        return $this->json(true);
    }

    public function json($success, $data = [])
    {
        $data['success'] = $success;
        return Json::encode($data);
    }

    public function actionApprove()
    {
        $id = \Yii::$app->request->post('id');

        $this->setApprove($id, 1);
    }

    public function actionDeny()
    {
        $id = \Yii::$app->request->post('id');

        $this->setApprove($id, -1);

    }

    public function actionApproveAll()
    {
        $ids = \Yii::$app->request->post('ids');

        foreach ($ids as $id) {
            $this->setApprove($id, 1);
        }

    }

    protected function setApprove($id, $value)
    {
        $modelClass = $this->modelClass;
        $row = $modelClass::findOne($id);

        if ($row) {
            if ($row->hasMethod('setIsApproved')) {
                $row->setIsApproved($value);
            } else {
                if ($row->hasAttribute('is_approved')) {
                    $row->is_approved = $value;
                } else {
                    $row->enabled = $value;
                }
                $row->save();
                echo "ok ";
            }
        }
    }

    public function actionDeleteFile()
    {
        $file_id = \Yii::$app->request->get('file_id');
        $id = \Yii::$app->request->get('id');
        $attribute = \Yii::$app->request->get('attribute');
        if ($file_id) {
            \Yii::$app->db->createCommand()->delete('media_files', ['id' => $file_id])->execute();
        }
        if ($id && $attribute) {
            $model = $this->findModel($id);
            $model->$attribute = "";
            $model->save();
        }
        echo "ok";
    }

    function isSecure()
    {
        return
            (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443;
    }

    public function beforeAction($action)
    {
        if (\Yii::$app->params['https'] && !$this->isSecure()) {
            $this->redirect('https://' . \Yii::$app->request->serverName . \Yii::$app->request->url);
        } else {
            return parent::beforeAction($action);
        }
    }

    public function actionItemsJson($q)
    {
        $modelData = \Yii::$app->request->get('model');
        $isMultiModel = is_array($modelData);
        $results = [];
        $models = (array)$modelData;
        foreach ($models as $model){
            $fullClass = ClassHelper::getClassPath($model);
            $items = $fullClass::find()->where(['like', 'name', $q])->all();
            foreach ($items as $item){
                $text = $item->name;
                if($isMultiModel){
                    $badge = "<span class=\"label label-info\">" . $item->getTypeName() . "</span> ";
                    $text = $badge . $text;
                    $results[] = ['id' => $item->className() . ":" . $item->id, 'text' => $text];
                } else {
                    $results[] = ['id' => $item->id, 'text' => $text];
                }
            }
        }
        echo Json::encode([
            'results' => $results
        ]);
    }

    public function actionRenderMultilistItem($formName, $id)
    {
        $modelData = \Yii::$app->request->get('model');
        $isMultiModel = is_array($modelData);
        if($isMultiModel){
            list($fullClass, $id) = explode(":", $id);
        } else {
            $fullClass = ClassHelper::getClassPath($modelData);
        }

        $item = $fullClass::findOne($id);
        echo MultiList::renderListItem($item, $formName, $isMultiModel);
    }

    public function actionUploadVariant($object_id, $id, $attribute, $variant)
    {
        $modelClass = $this->modelClass;
        $file = UploadedFile::getInstanceByName('file');
        if($file && $attribute != "id"){
            $imageContent = file_get_contents( $file->tempName );
            $type = $modelClass::tableName() . "." . $attribute;
            $image_id = \Yii::$app->imageManager->updateVariant($type, $imageContent, $variant, $id);
            $model = $this->findModel( $object_id );
            $model->$attribute = $image_id;
            $model->save();
        }
        echo ImageWidget::renderVariants($type, $image_id, $attribute, $object_id);
    }


}
