<?
header('Content-Type: text/html; charset=utf-8');
$lang = 'ru';
if (
	isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') &&
	isset($_POST['do']) &&
	isset($_POST['x']) && ($_POST['x']=='secure')
	)
{
	include_once($_SERVER["DOCUMENT_ROOT"].'/libs/mysql.php');
	include_once($_SERVER["DOCUMENT_ROOT"].'/libs/api.php');

	require_once '../get_encr_key.php';
	if (
		$api->Managers->check_auth() == true
	)
	{
		if (
			($_POST['do'] == 'settings') &&
			(isset($_POST['pass'])) && ($_POST['pass'] != '') &&
			(isset($_POST['pass_old'])) && ($_POST['pass_old'] != '')
			)
		{
			$pass_old 	= $api->Strings->pr($_POST['pass_old']);
			$pass 		= $api->Strings->pr($_POST['pass']);

			if ($api->Managers->check_auth())
			{
				$table = 'i_manager_users';
				$id_user = $api->Managers->man_id;
				$pole = 'pass';
			}

			$s=mysql_query("SELECT * FROM `".$table."` WHERE `id`='".$id_user."' LIMIT 1");
			if (mysql_num_rows($s) > 0)
				$r=mysql_fetch_array($s);

			
			$sql_pas = ""; $up = 1;
			
			if ($pass_old != '')
			{
				$r[$pole] = decryptPassword($r[$pole], $encryption_key);
				if ($pass_old == $r[$pole])
				{
					if ($pass == $r[$pole])
					{

						$up = 0;
						echo '
						<script type="text/javascript">
							jQuery("#user_pass_info1").html("новый пароль не должен совпадать со старым").css("display", "inline-block");
						</script>';
					}
					else
						$sql_pas = "`".$pole."`='".encryptPassword($pass, $encryption_key)."'";
				}
				else
				{
					$up = 0;
					echo '
					<script type="text/javascript">
						jQuery("#user_pass_info_old").html("старый пароль указан не верно").css("display", "inline-block");
					</script>';
				}
			}

			if ($up == 1)
			{
				$sql = "UPDATE `".$table."` SET ".$sql_pas." WHERE `id`='".$id_user."'";
				$update = mysql_query($sql);

				/*
				content.message = "Поздравляем, Вы успешно обновили пароль";
					content.title = "";
					content.icon = "fa fa-bell";
					content.url = "";
					content.target = "";
					jQuery.notify(content,{
						type: "primary",
						placement: {
							from: "top",
							align: "center"
						},
						time: 1000,
						delay: 0,
					});
				*/

				echo '
				<script type="text/javascript">
					setTimeout(function() { self.location = "/settings/change.php"; }, 2000);
				</script>
				<span style="color:#53b374">Поздравляем, Вы успешно обновили пароль</span>
				';
			}
		}
	}

	exit;
}
?>
