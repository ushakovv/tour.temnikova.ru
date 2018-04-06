<?php

namespace admin\controllers;

/**
 * VkAlbumController implements the CRUD actions for VkAlbum model.
 */
class VkAlbumController extends \admin\components\AdminController
{
	public $modelClass = 'app\models\VkAlbum';
	public $searchModelClass = 'app\models\search\VkAlbumSearch';

	public $sectionTitle = 'Альбом из VK';

}
