<?
$lang="ru";
$title="Вход в личный кабинет";
$keywords="";
$description="";
require($_SERVER["DOCUMENT_ROOT"]."/libs/header.php");

if ($api->Managers->check_auth() == false) 
	require($_SERVER["DOCUMENT_ROOT"]."/auth.php");
else
	echo '
	<script type="text/javascript">
		//setTimeout(function() { self.location = "/"; }, 50);
	</script>
	';
	
require($_SERVER["DOCUMENT_ROOT"]."/libs/footer.php");?>