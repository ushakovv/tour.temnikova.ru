<?php
use admin\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;

AppAsset::register($this);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<!-- Template Name: Clip-Two - Responsive Admin Template build with Twitter Bootstrap 3.x | Author: ClipTheme -->
<!--[if IE 8]><html class="ie8" lang="en"><![endif]-->
<!--[if IE 9]><html class="ie9" lang="en"><![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- start: HEAD -->
<head>
	<?= Html::csrfMetaTags() ?>
	<title><?= Html::encode($this->title) ?></title>

	<link href="http://fonts.googleapis.com/css?family=Crete+Round:400italic" rel="stylesheet" type="text/css" />

	<?php $this->head() ?>
	<!-- start: META -->
	<!--[if IE]><meta http-equiv='X-UA-Compatible' content="IE=edge,IE=9,IE=8,chrome=1" /><![endif]-->
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta content="" name="description" />
	<meta content="" name="author" />
	<!-- end: META -->
	<!-- start: GOOGLE FONTS -->
	<!-- end: GOOGLE FONTS -->
	<!-- start: MAIN CSS -->
	<link rel="stylesheet" href="/vendor/fontawesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="/vendor/themify-icons/themify-icons.min.css">
	<link href="/vendor/animate.css/animate.min.css" rel="stylesheet" media="screen">
	<link href="/vendor/perfect-scrollbar/perfect-scrollbar.min.css" rel="stylesheet" media="screen">
	<link href="/vendor/switchery/switchery.min.css" rel="stylesheet" media="screen">
	<!-- end: MAIN CSS -->
	<!-- start: CLIP-TWO CSS -->
	<!--<link rel="stylesheet" href="/vendor/assets/css/styles.css">
	<link rel="stylesheet" href="/vendor/assets/css/plugins.css">
	<link rel="stylesheet" href="/vendor/assets/css/themes/theme-1.css" id="skin_color" />-->
	<!-- end: CLIP-TWO CSS -->
	<!-- start: CSS REQUIRED FOR THIS PAGE ONLY -->
	<!-- end: CSS REQUIRED FOR THIS PAGE ONLY -->
</head>
<!-- end: HEAD -->
<body>
<?php $this->beginBody() ?>
<div id="app">
	<!-- sidebar -->
	<div class="sidebar app-aside" id="sidebar">
		<div class="sidebar-container perfect-scrollbar">
			<nav>
				<!-- start: SEARCH FORM -->
				<!--<div class="search-form">
					<a class="s-open" href="#">
						<i class="ti-search"></i>
					</a>
					<form class="navbar-form" role="search">
						<a class="s-remove" href="#" target=".navbar-form">
							<i class="ti-close"></i>
						</a>
						<div class="form-group">
							<input type="text" class="form-control" placeholder="Search...">
							<button class="btn search-button" type="submit">
								<i class="ti-search"></i>
							</button>
						</div>
					</form>
				</div>-->
				<!-- end: SEARCH FORM -->
				<!-- start: MAIN NAVIGATION MENU -->
				<div class="navbar-title">
					<span>Control panel</span>
				</div>

				<?php

					/*$user_role_id = null;
					if (!Yii::$app->user->isGuest) {
						$user = Yii::$app->user->identity;
						$user_role_id = $user->role_id;
					}

					$adminSectionGroup = new \admin\models\AdminSectionGroup();
					$leftMenuItems = $adminSectionGroup->getCmsMenu($user_role_id, ['r' => 1]);

					echo Nav::widget([
						'options' => ['class' => 'main-navigation-menu'],
						'items' => $leftMenuItems
					]);*/

				?>

                <?php
                    if (!Yii::$app->user->isGuest) {
                        echo \core\components\AdminHtml::menuFromDb();
                    }
                ?>
				<!-- start: CORE FEATURES -->
				<!--<div class="navbar-title">
					<span>Core Features</span>
				</div>
				<ul class="folders">
					<li>
						<a href="pages_calendar.html">
							<div class="item-content">
								<div class="item-media">
									<span class="fa-stack"> <i class="fa fa-square fa-stack-2x"></i> <i class="fa fa-terminal fa-stack-1x fa-inverse"></i> </span>
								</div>
								<div class="item-inner">
									<span class="title"> Calendar </span>
								</div>
							</div>
						</a>
					</li>
					<li>
						<a href="pages_messages.html">
							<div class="item-content">
								<div class="item-media">
									<span class="fa-stack"> <i class="fa fa-square fa-stack-2x"></i> <i class="fa fa-folder-open-o fa-stack-1x fa-inverse"></i> </span>
								</div>
								<div class="item-inner">
									<span class="title"> Messages </span>
								</div>
							</div>
						</a>
					</li>
				</ul>
				<div class="wrapper">
					<a href="documentation.html" class="button-o">
						<i class="ti-help"></i>
						<span>Documentation</span>
					</a>
				</div>-->
				<!-- end: DOCUMENTATION BUTTON -->
			</nav>
		</div>
	</div>
	<!-- / sidebar -->
	<div class="app-content">
		<!-- start: TOP NAVBAR -->
		<header class="navbar navbar-default navbar-static-top">
			<!-- start: NAVBAR HEADER -->
			<div class="navbar-header">
				<a href="#" class="sidebar-mobile-toggler pull-left hidden-md hidden-lg" class="btn btn-navbar sidebar-toggle" data-toggle-class="app-slide-off" data-toggle-target="#app" data-toggle-click-outside="#sidebar">
					<i class="ti-align-justify"></i>
				</a>
				<a class="navbar-brand" href="#">
					<img  alt="Temnikova" />
				</a>
				<a href="#" class="sidebar-toggler pull-right visible-md visible-lg" data-toggle-class="app-sidebar-closed" data-toggle-target="#app">
					<i class="ti-align-justify"></i>
				</a>
			</div>
			<!-- end: NAVBAR HEADER -->
			<!-- start: NAVBAR COLLAPSE -->
			<div class="navbar-collapse collapse">
				<ul class="nav navbar-right">

					<!-- start: LANGUAGE SWITCHER -->
					<!-- start: USER OPTIONS DROPDOWN -->

                    <?php
                        if (!Yii::$app->user->isGuest) {
                            ?>
                            <li class="dropdown current-user">
                                <a href class="dropdown-toggle" data-toggle="dropdown">
                                    <span class="username"><?= Yii::$app->user->identity->username ?> <i class="ti-angle-down"></i></i></span>
                                </a>
                                <ul class="dropdown-menu dropdown-dark">
                                    <li>
                                        <a href="<?= \yii\helpers\Url::toRoute('password/index') ?>">
                                            Change password
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?= \yii\helpers\Url::toRoute('default/logout') ?>">
                                            Log Out
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <?php
                        }
                            ?>
					<!-- end: USER OPTIONS DROPDOWN -->
				</ul>
				<!-- start: MENU TOGGLER FOR MOBILE DEVICES -->
				<div class="close-handle visible-xs-block menu-toggler" data-toggle="collapse" href=".navbar-collapse">
					<div class="arrow-left"></div>
					<div class="arrow-right"></div>
				</div>
				<!-- end: MENU TOGGLER FOR MOBILE DEVICES -->
			</div>
			<!-- end: NAVBAR COLLAPSE -->
		</header>
		<!-- end: TOP NAVBAR -->
		<div class="main-content" >
			<div class="wrap-content container" id="container">
				<!-- start: PAGE TITLE -->
				<!--<section id="page-title">
					<div class="row">
						<div class="col-sm-8">
							<h1 class="mainTitle">Starter Page</h1>
							<span class="mainDescription">Use this page to start from scratch and put your custom content</span>
						</div>
						<ol class="breadcrumb">
							<li>
								<span>Pages</span>
							</li>
							<li class="active">
								<span>Blank Page</span>
							</li>
						</ol>
					</div>
				</section>-->
				<!-- end: PAGE TITLE -->
				<?= $content ?>
			</div>
		</div>
	</div>
</div>
<!-- start: MAIN JAVASCRIPTS -->
<!-- end: MAIN JAVASCRIPTS -->
<!-- start: CLIP-TWO JAVASCRIPTS -->
<!--<script src="/vendor/assets/js/main.js"></script>-->
<!-- start: JavaScript Event Handlers for this page -->
<!-- end: JavaScript Event Handlers for this page -->
<!-- end: CLIP-TWO JAVASCRIPTS -->
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>