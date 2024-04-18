<?
$lang="ru";
if (
	(isset($_SERVER['HTTP_X_REQUESTED_WITH'])) && 
	($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') && 
	(isset($_POST['do'])) && 
	($_POST['do'] == 'logout') && 
	(isset($_POST['x'])) && 
	($_POST['x']=='secure'))
{

	include_once($_SERVER["DOCUMENT_ROOT"].'/libs/mysql.php');
	include_once($_SERVER["DOCUMENT_ROOT"].'/libs/api.php');
	
	@header('Content-Type: text/html; charset=utf-8');
	
	if ($api->Managers->check_auth() == true)
		$api->Managers->logout_user();
		
	echo '
	<script type="text/javascript">
		jQuery.cookie("man_auth_site", "", { expires: 1, path: "/"});
		jQuery(document).ready(function()
		{ 			
			setTimeout(function() { self.location = "/log.php"; }, 50);
		});
	</script>';

} else {
  header('HTTP/1.0 404 Not Found');
}
?>