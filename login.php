<?
require_once "get_encr_key.php";
// $encryption_key = "ZHIRKAIMB";
//error_reporting(E_ALL);
//ini_set('display_errors', TRUE);
//ini_set('display_startup_errors', TRUE);
if (
	(isset($_SERVER['HTTP_X_REQUESTED_WITH'])) && 
	($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') && 
	(isset($_POST['do'])) && 
	($_POST['do'] == 'auth') && 
	(isset($_POST['login'])) && 
	($_POST['login'] != '') && 
	(isset($_POST['pass'])) && 
	($_POST['pass'] != '') && 
	(isset($_POST['x'])) && 
	($_POST['x']=='secure'))
{

	include_once($_SERVER["DOCUMENT_ROOT"].'/libs/mysql.php');
	include_once($_SERVER["DOCUMENT_ROOT"].'/libs/api.php');
	
	@header('Content-Type: text/html; charset=utf-8');
	
	# Переменные
	$login = str_replace(';','',$api->Strings->pr($_POST['login']));
	$pass  = str_replace(';','',$api->Strings->pr($_POST['pass']));
	/*
	require_once($_SERVER["DOCUMENT_ROOT"]."/recaptchalib.php");
		
	//секретный ключ
	$secret = "6LfNxmUUAAAAAN4fgiFd40-mKwyv0NPYUOdr7XS6";
	//ответ
	$response = null;
	//проверка секретного ключа
	$reCaptcha = new ReCaptcha($secret);
			
	if ($_POST["g-recaptcha-response"]) {
		$response = $reCaptcha->verifyResponse(
			$_SERVER["REMOTE_ADDR"],
			$_POST["g-recaptcha-response"]
		);
	}
 	*/
	echo '
	<script type="text/javascript">';
	
	/*if ($response != null && $response->success) 
	{*/					
		// echo $login;
		// echo encryptPassword($pass, $encryption_key);
		// echo decryptPassword("test", $encryption_key);
		if ($api->Managers->check_login($login, $pass) != false) // encryptPassword($pass, $encryption_key)
		{
			
			$password = $api->Managers->check_login($login, $pass);
			
			// list($password, $iv) = explode('::', base64_decode($password), 2);

			$normpassword = decryptPassword($password, $encryption_key);

			// echo $normpassword;
			// echo $password;
			
			if($pass == $normpassword){
				$api->Managers->login_user($login, $pass);
				// echo $normpassword;
				if (isset($_POST["remember"]) && intval($_POST["remember"])==1) {			
					echo 'jQuery.cookie("man_auth_site", "'.$login."|".sha1($normpassword).'", { expires: 7, path: "/"}); ';
				}
				
				$link = '/order/';
				if ($api->Managers->man_block == 4)		$link = '/order/add.php';
				else if ($api->Managers->man_block == 5)	$link = '/order/?status=10';
				
				echo ' 			
				jQuery(document).ready(function()
				{ 			
					setTimeout(function() { self.location = "'.$link.'"; }, 50);
				});
				';
			}
			else if (
				$api->Managers->check_block($login, $pass) == true
			)
			{
				echo '		
				jQuery("#protocolLog").html("<p style=\"color:#f00;margin:10px 0;\">Ваш логин заблокирован, обратитесь к администратору.</p>").slideDown(700);				
				';	
			}
			else 
			{
				//$api->Managers->check_log($login, $pass); 
				echo '		
				jQuery("#protocolLog").html("<p style=\"color:#f00;margin:10px 0;\">Неверный логин или пароль!</p>").slideDown(700);				
				';
			}
		
		} 

		else if (
			$api->Managers->check_block($login, $pass) == true
		)
		{
			echo '		
			jQuery("#protocolLog").html("<p style=\"color:#f00;margin:10px 0;\">Ваш логин заблокирован, обратитесь к администратору.</p>").slideDown(700);				
			';	
		}
		else 
		{
			//$api->Managers->check_log($login, $pass); 
			echo '		
			jQuery("#protocolLog").html("<p style=\"color:#f00;margin:10px 0;\">Неверный логин или пароль!</p>").slideDown(700);				
			';
		}
	/*}
	else 
	{
		echo '				
		jQuery("#protocolLog").html("<p style=\"color:#f00;margin:10px 0;\">Вы точно человек?</p>").slideDown(700);			
		';
	}*/
	
	echo '
	</script>';
}

?>