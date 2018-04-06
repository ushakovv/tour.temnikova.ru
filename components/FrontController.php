<?php

namespace app\components;

use app\models\Contacts;
use app\models\Pages;
use app\models\Sections;
use app\models\User;
use core\components\ContentManager;
use Yii;
use yii\base\Controller;
use yii\helpers\Json;
use yii\web\BadRequestHttpException;
use yii\web\Response;
use app\components\SxGeo;

class FrontController extends \yii\web\Controller
{
    const PAGE_CACHE_TIME = 300;

	const MAX_FILE_SIZE_MB = 5;

    public $aErrors = 0;

    public $aDataFields = 0;

    public $layout = "main.twig";

    public $currentPage;

    public $pageTitle;

    public $pageDescription;

    public $contacts;

    public $data;

    public $headerClass = "";

    public $sLang = "en";

	public $app = null;

    public $indexingMode = false;

    public $js = [];

    public $css = [];

	public $basket = [];

    public $classBody = 'homepage';

    public function beforeAction($action)
    {
	    $this->enableCsrfValidation = false;

//        $page = Pages::findPage(Yii::$app->request->pathInfo);
//
//
//        if($page){
//            $this->currentPage = $page;
//            ContentManager::preFetchGroup('pages', $page->id);
//        }
//        else{
//            //404
//            $this->currentPage = ['title' => 'Страница не найдена | 404'];
//        }

        return parent::beforeAction($action);
    }

    public function init()
    {
	    $this->basket = isset($_COOKIE['basket']) ? JSON::decode($_COOKIE['basket']) : [];
    }

    public function cache($key, $time, $func)
    {
        $cache = \Yii::$app->cache;
        $data = $cache->get($key);
        if ($data === false) {
            $data = $func();
            $cache->set($key, $data, $time);
        }
        return $data;
    }

    public function json($success, $response = [])
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $response['success'] = $success;
        return $response;
    }

    function isSecure()
    {
        return
            (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443;
    }

    /**
     * @return null|User
     */
    function getUser()
    {
        return \Yii::$app->user->getIdentity();
    }

    public function render($view, $params = [])
    {
        if(isset($_GET['nolayout'])){
            $this->indexingMode = true;
            return $this->renderPartial($view, $params);
        } else {
            return parent::render($view, $params);
        }

    }

    public function createLangProperty($name)
    {
        $name = $name . '_' . \Yii::$app->language;
        return $name;
    }

	public function actionImage()
	{
		$aReply = ['state' => 'fail'];
		if(!empty($_FILES)) {

			$aEnabledExtension = ['jpg', 'jpeg', 'gif', 'png'];
			$sKey = isset($_POST[\Yii::$app->request->csrfParam]) ? $_POST[\Yii::$app->request->csrfParam] : 'file';
			$aResult = \Yii::$app->session->get($sKey);

			foreach($_FILES as $key=>$aFile) {
				$aName = explode('.', $aFile['name']);
				if(!in_array ( $aName[count($aName)-1], $aEnabledExtension ) ) {
					$aReply['sError'] = 'Загрузка файлов данного типа запрещена';
				} elseif(0 != $aFile['error']) {
					$aReply['sError'] = 'Ошибка загрузки';
				} elseif($aFile['size'] >= (self::MAX_FILE_SIZE_MB * 1024 *1024)) {
					$aReply['sError'] = 'Размер файла слишком большой';
				} else {
					$content = file_get_contents($aFile['tmp_name']);
					if($content) {
						$imgData = \Yii::$app->imageManager->put(key($_FILES) . '.image_id', $content);
						$aResult[] = $imgData['id'];
						$aReply = ['state' => 'success', 'image_id'=> $imgData['id']];
					}
				}
			}
			\Yii::$app->session->set($sKey, $aResult);
		}
		$this->sendResponse($aReply);
	}

	public function actionDocs()
	{
		$aReply = ['state' => 'fail'];
		if(!empty($_FILES))
		{
			$aEnabledExtension = ['doc', 'docx', 'pdf', 'txt'];
			foreach($_FILES as $key=>$aFile) {
				$aName = explode('.', $aFile['name']);
				if(!in_array ( $aName[count($aName)-1], $aEnabledExtension ) ) {
					$aReply['sError'] = 'Загрузка файлов данного типа запрещена';
				} elseif(0 != $aFile['error']) {
					$aReply['sError'] = 'Ошибка загрузки';
				} elseif($aFile['size'] >= (self::MAX_FILE_SIZE_MB * 1024 *1024)) {
					$aReply['sError'] = 'Размер файла слишком большой';
				} else {
					$content = file_get_contents($aFile['tmp_name']);
					if($content) {
						$sUrl = \Yii::$app->fileManager->put( key($_FILES), $content, $aFile['name']);
						if($sUrl){
							$aReply = ['state' => 'success', 'url'=> $sUrl];
						} else {
							$aReply['sError'] = 'Ошибка загрузки';
						}
					}
				}
			}
		}
		$this->sendResponse($aReply);
	}

	public function sendResponse($response)
	{
		$state = Yii::$app->getResponse();
		$state->format = \yii\web\Response::FORMAT_JSON;
		$state->data = $response;
		$state->send();
	}




}

