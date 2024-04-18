<?
$lang="ru";
$title="Забыли пароль?";
$keywords="";
$description="";

if (
	isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') && 
	isset($_POST['do']) &&
	isset($_POST['x']) && ($_POST['x']=='secure')
	)
{
	
	include_once($_SERVER["DOCUMENT_ROOT"].'/libs/mysql.php');
	include_once($_SERVER["DOCUMENT_ROOT"].'/libs/api.php');
	
	if (
		($_POST['do'] == 'nextForget') &&
		(isset($_POST['login']) && $api->Strings->pr($_POST['login']) != '')
	)
	{
		$login = $api->Strings->pr($_POST['login']);
		
		$s=mysql_query("SELECT * FROM `i_manager_users` WHERE `phone`='".$login."' LIMIT 1");
		if (mysql_num_rows($s)==0)
		{
			echo '
			<div style="color:#f00;">Телефон не существует</div>
			<script type="text/javascript">
				jQuery(".nextForget").show();
			</script>
			';
		}	
		else
		{
			$r=mysql_fetch_array($s);
			/*						
			// подключение wazzup
			include_once($_SERVER["DOCUMENT_ROOT"].'/wazzup/wazzup.php');

			$sms_text = 'Ваш логин: '.$r["login"].'
Ваш пароль: '.$r["pass"];
			$phone = $r["phone"];
			$phone_to = str_replace('+', '', str_replace(' ', '', str_replace('(', '', str_replace(')', '', $phone))));
			// отправка смс на whatsapp
			$messageId = $wazzup->Watsapp->sendMessage($phone_to, $sms_text);
			
			echo '<p style="color:#53b374">Ваш логин/пароль были отправлены на Ваш whatsapp номер!</p>';
			*/
		}
	}
	
	exit;
}

require($_SERVER["DOCUMENT_ROOT"]."/libs/header.php");

if ($api->Managers->check_auth() == false)
{
	?>
    <div class="wrapper wrapper-login">
        <div class="container container-login animated fadeIn">
            <h3 class="text-center">Забыли пароль?</h3>
            <p class="center"><a href="/"><img style="max-width:220px;" src="/library/img/logo.png" /></a></p>
            <div class="login-form">
                <div class="form-group form-floating-label">
                    <input id="login" onKeyDown="funcNext(event);" type="text" class="form-control input-border-bottom phone" required />
                    <label for="login" class="placeholder">Телефон</label>
                </div>
                <div id="nextStep"></div>
                <div class="form-action mb-3 nextForget">            
                    <a onClick="nextForget()" class="btn btn-primary btn-rounded btn-login">Далее</a>
                </div>
                <div class="loading"><img src="/library/img/load.gif" /> Ваш запрос обрабатывается....</div>
                <div class="login-account">
                    <span class="msg"><a style="color:#1572E8" href="log.php">Войти</a> под своим логином и паролем</span>
                </div>
            </div>
        </div>    
    </div>
    <?	
}
else
	echo '
	<script type="text/javascript">
		setTimeout(function() { self.location = "/order/"; }, 50);
	</script>
	';
	
require($_SERVER["DOCUMENT_ROOT"]."/libs/footer.php");?>