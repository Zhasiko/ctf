<?php
//error_reporting(E_ALL);
//ini_set('display_errors', TRUE);
//ini_set('display_startup_errors', TRUE);
include_once($_SERVER['DOCUMENT_ROOT']."/libs/mysql.php");
include_once($_SERVER['DOCUMENT_ROOT']."/libs/api.php");
$_SESSION["version"]=$lang;
// проверка авторизации - запомнить меня
$api->Managers->pr_cookieM();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<title><?=(@$title2!='' ? $title2 : $title)?></title>
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta http-equiv = "X-UA-Compatible" content="IE=11" />
    <meta name="autor" content="deweb.kz" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=0">
	<meta name="format-detection" content="telephone=no">
	<meta name="HandheldFriendly" content="true">
	<link rel="shortcut icon" href="/library/img/favicon.ico">
	<script type="text/javascript" src="/library/js/plugin/webfont/webfont.min.js"></script>
	<script type="text/javascript">
		WebFont.load({
			google: {"families":["Lato:300,400,700,900"]},
			custom: {"families":["Flaticon", "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"], urls: ['/library/css/fonts.min.css']},
			active: function() {
				sessionStorage.fonts = true;
			}
		});
	</script>
	<link rel="stylesheet" href="/library/css/bootstrap.min.css?ver=1.1" />
	<link rel="stylesheet" href="/library/css/atlantis.css?ver=1.2" />
    <link rel="stylesheet" href="/library/css/style.css?ver=1.8" />
    <script type="text/javascript" src="/library/js/core/jquery.3.2.1.min.js"></script>
</head>
<body<?=($api->Managers->check_auth() == false && ($_SERVER['PHP_SELF'] == '/index.php' || $_SERVER['PHP_SELF'] == '/log.php' || $_SERVER['PHP_SELF'] == '/forget.php') ? ' class="login"' : '')?>>
<div id="log_protocol"></div>
<?
$class_menu = '';
if (
	(
		($api->Managers->check_auth() == true) &&
		(		
			$_SERVER['PHP_SELF'] == '/order/index.php' ||
			$_SERVER['PHP_SELF'] == '/order/fresh.php' ||
			$_SERVER['PHP_SELF'] == '/basket/index.php' ||
			$_SERVER['PHP_SELF'] == '/settings/baza/index.php'
		)
	)
)
{
	$class_menu = ' sidebar_minimize';
}
?>
<div class="wrapper fullheight-side no-box-shadow-style<?=$class_menu?>">
	<? if ($api->Managers->check_auth() == true) { ?>
    <div class="logo-header position-fixed" data-background-color="dark">
        <a class="logo">
            <img src="/library/img/logo_.png" alt="navbar brand" class="navbar-brand" style="width:90px;" />
        </a>
        <button class="navbar-toggler sidenav-toggler ml-auto" type="button" data-toggle="collapse" data-target="collapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon">
                <i class="icon-menu"></i>
            </span>
        </button>
        <?php /*?><button class="topbar-toggler more"><i class="icon-options-vertical"></i></button><?php */?>
        <div class="nav-toggle">
            <button class="btn btn-toggle toggle-sidebar">
                <i class="icon-menu"></i>
            </button>
        </div>
    </div>		
    <div class="sidebar" data-background-color="dark">	
        <div class="sidebar-wrapper scrollbar scrollbar-inner">
            <div class="sidebar-content">                
                <ul class="nav nav-warning">    
                	<? if ($api->Managers->check_auth() == true) { ?>
						<? if (
								$api->Managers->man_block == 1 || $api->Managers->man_block == 2 || $api->Managers->man_block == 5 ||							
								$api->Managers->man_block == 4
						) { ?>

                    
					<li class="nav-item<?=($_SERVER["PHP_SELF"] == '/order/add.php' ? ' active' : '')?>">
                        <a href="/order/add.php">
                            <i class="fas fa-briefcase"></i>
                            <p>Новая задача</p>								
                        </a>							
                    </li>
						<? } ?>
						
                    <li class="nav-item<?=(substr($_SERVER["PHP_SELF"], 0, 7) == '/order/' && $_SERVER["PHP_SELF"] != '/order/add.php' && $_SERVER["PHP_SELF"] != '/order/fresh.php' ? ' active' : '')?>">
                        <a href="/order/">
                            <i class="fas fa-laptop-code"></i>
                            <p>Задачи</p>
                        </a>
                    </li>     
						<!-- <? if ($api->Managers->man_block == 1 || $api->Managers->man_block == 5) { ?>
					<li class="nav-item<?=(substr($_SERVER["PHP_SELF"], 0, 7) == '/basket/' ? ' active' : '')?>">
                        <a href="/basket/index.php">
                            <i class="fas fa-box-open"></i>
                            <p>Корзина</p>								
                        </a>							
                    </li>
						<? } ?> -->
                    <? } ?>
					<li class="nav-item">
                    	<hr />
                    </li>
                   		<? if ($api->Managers->man_block == 1) { ?>
                    <li class="nav-item<?=(substr($_SERVER["PHP_SELF"], 0, 7) == '/staff/' ? ' active' : '')?>">
                        <a href="/staff/">
                            <i class="fas fa-user-graduate"></i>
                            <p>Пользователи</p>
                        </a>
                    </li>
						<? } ?> 
						<!-- <? if ($api->Managers->man_block == 1 || $api->Managers->man_block == 2 || $api->Managers->man_block == 5) { ?> -->
					<!-- <li class="nav-item<?=(substr($_SERVER["PHP_SELF"], 0, 10) == '/settings/' ? ' active submenu' : '')?>">
                        <a data-toggle="collapse" href="#settings">
                            <i class="far fa-chart-bar"></i>
                            <p>Настройки</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse<?=(substr($_SERVER["PHP_SELF"], 0, 10) == '/settings/' ? ' show' : '')?>" id="settings">
                            <ul class="nav nav-collapse">
                                <li>
                                    <a href="/settings/baza/"<?=(substr($_SERVER["PHP_SELF"], 0, 15) == '/settings/baza/' ? ' class="active"' : '')?>>
                                        <span class="sub-item">База машин</span>
                                    </a>
                                </li>
								<li>
                                    <a href="/settings/baza_load/"<?=(substr($_SERVER["PHP_SELF"], 0, 20) == '/settings/baza_load/' ? ' class="active"' : '')?>>
                                        <span class="sub-item">Загрузить файл базы</span>
                                    </a>
                                </li>
								<li>
                                    <a href="/settings/temp_pdf/"<?=(substr($_SERVER["PHP_SELF"], 0, 19) == '/settings/temp_pdf/' ? ' class="active"' : '')?>>
                                        <span class="sub-item">Шаблоны для PDF</span>
                                    </a>
                                </li>
								<li>
                                    <a href="/settings/phones/"<?=(substr($_SERVER["PHP_SELF"], 0, 17) == '/settings/phones/' ? ' class="active"' : '')?>>
                                        <span class="sub-item">Справочник телефонов</span>
                                    </a>
                                </li>
							</ul>
						</div>                                                            
                    </li>    -->
                    <!-- <li class="nav-item">
                    	<hr />
                    </li>                                         
                        <? } ?> -->
                        <? if (
								$api->Managers->man_block == 1 || $api->Managers->man_block == 2 || $api->Managers->man_block == 5 ||							
								$api->Managers->man_block == 4
						) { ?>
                    <li class="nav-item<?=($_SERVER["PHP_SELF"] == '/settings/change.php' ? ' active' : '')?>">
                        <a href="/settings/change.php">
                            <i class="fas fa-cogs"></i>
                            <p>Изменить пароль</p>
                        </a>
                    </li>
                    <? } ?>
                    <li class="nav-item">
                        <a class="logout_btn">
                            <i class="icon-logout"></i>
                            <p>Выход из системы</p>
                        </a>
                    </li>                                                
                </ul>                
            </div>
        </div>
    </div>		    
    <? } ?>
    	<? if (
			($_SERVER['PHP_SELF'] != '/log.php') &&
			($_SERVER['PHP_SELF'] != '/forget.php') &&
			($_SERVER['PHP_SELF'] != '/index.php')		
		) { ?>
    <div class="<?=($api->Managers->check_auth() == true ? 'main-panel ' : '')?>full-height">
        <div class="container">
            <div class="page-inner">            		
                <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pb-3">

                    <div class="title" style = "color: white;"><h2 class="pb-2 fw-bold"><?=$title?></h2></div>
                    <? if ($api->Managers->check_auth() == true) { ?>
                    <div class="ml-md-auto pb-2 py-md-0">                                                    	
                        <?  
						if ($api->Managers->man_block == 1)
                        {                       
	                        if (substr($_SERVER["PHP_SELF"], 0, 7) == '/staff/' && $_SERVER["PHP_SELF"] != '/staff/add.php')
								echo '<a class="btn btn-primary btn-round'.($api->Strings->check_smartphone() == true ? ' mt-3' : '').'" href="add.php">Добавить пользователя</a>';
						}
						
						if (
							$api->Managers->man_block == 1 || 
							$api->Managers->man_block == 2 || 
							$api->Managers->man_block == 5
						) {
							
							// if ($_SERVER["PHP_SELF"] == '/order/index.php')
    	                    // 	echo '
							// 	<a class="get_ExcelOder btn btn-primary btn-round'.($api->Strings->check_smartphone() == true ? ' mt-3' : '').'" onclick="get_ExcelOder()">Выгрузить Excel</a>
							// 	<span class="loading" id="load_ExcelOder"><img src="/library/img/load.gif" /></span>
							// 	<span id="protocol_ExcelOder"></span>
							// 	';
							
							if (substr($_SERVER["PHP_SELF"], 0, 15) == '/settings/baza/' && $_SERVER["PHP_SELF"] != '/settings/baza/add.php')
    	                    	echo '<a class="btn btn-primary btn-round'.($api->Strings->check_smartphone() == true ? ' mt-3' : '').'" href="add.php">Добавить запись</a>';
						
							if (substr($_SERVER["PHP_SELF"], 0, 19) == '/settings/temp_pdf/' && $_SERVER["PHP_SELF"] != '/settings/temp_pdf/add.php')
    	                    	echo '<a class="btn btn-primary btn-round'.($api->Strings->check_smartphone() == true ? ' mt-3' : '').'" href="add.php">Добавить шаблон</a>';
							
							if (substr($_SERVER["PHP_SELF"], 0, 17) == '/settings/phones/' && $_SERVER["PHP_SELF"] != '/settings/phones/add.php')
    	                    	echo '<a class="btn btn-primary btn-round'.($api->Strings->check_smartphone() == true ? ' mt-3' : '').'" href="add.php">Добавить телефон</a>';
						}
	
						if ($api->Managers->man_block == 4)
                        {
							if (substr($_SERVER["PHP_SELF"], 0, 7) == '/order/' && $_SERVER["PHP_SELF"] != '/order/add.php')
    	                    	echo '<a class="btn btn-primary btn-round'.($api->Strings->check_smartphone() == true ? ' mt-3' : '').'" href="add.php">Добавить заявку</a>';
						}
                        ?>							
                    </div>
                    <? } ?>
                </div>	                                         													
		<? } ?>