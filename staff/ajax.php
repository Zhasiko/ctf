<?
require_once '../get_encr_key.php';




header('Content-Type: text/html; charset=utf-8');
$lang = 'ru';
if (
	isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') &&
	isset($_POST['do']) &&
	isset($_POST['x']) && ($_POST['x']=='secure')
	)
{

	include_once($_SERVER['DOCUMENT_ROOT']."/libs/mysql.php");
	include_once($_SERVER['DOCUMENT_ROOT']."/libs/api.php");

	if (
		$api->Managers->check_auth() == true &&
		$api->Managers->man_block == 1
	)
	{
		if (
			($_POST['do'] == 'addUser') &&
			(isset($_POST['name']) && $api->Strings->pr($_POST['name']) != '') &&
			(isset($_POST['login']) && $_POST["login"] != '') &&
			(isset($_POST['pass']) && $_POST['pass'] != '') &&
			(isset($_POST['phone']) && $_POST['phone'] != '') &&
			(isset($_POST['cat']) && intval($_POST['cat']) != 0)
		)
		{
			$active = intval($_POST["active"]);			
			$cat = intval($_POST["cat"]);
			$name = trim($api->Strings->pr($_POST["name"]));
			$login = trim($api->Strings->pr($_POST["login"]));
			$pass = encryptPassword(trim($api->Strings->pr($_POST["pass"])), $encryption_key);
			$phone = trim($api->Strings->pr($_POST["phone"]));
						
			if ($api->Managers->man_block == 1)
			{
				$s=mysql_query("SELECT `id` FROM `i_manager_users` WHERE `login`='".$login."' LIMIT 1");
				if (mysql_num_rows($s) == 1)
				{
					echo '
					<script type="text/javascript">
						jQuery("#error_login").html("Пользователь с таким Логином уже существует").css("display", "inline-block");
						jQuery("#login").css("border-color", "#f00");
						jQuery("#login").focus();
						jQuery(".action").show();
					</script>
					';
				}
				else
				{
					$sql_insert = "INSERT INTO `i_manager_users` (`id_section`, `login`, `pass`, `active`, `name`, `phone`) VALUES ('".$cat."', '".$login."', '".$pass."', '".$active."', '".addslashes($name)."', '".$phone."')";
					$insert = mysql_query($sql_insert);

					if ($cat == 1)			$u_name = 'админа';
					else if ($cat == 2)		$u_name = 'преподователя';
					else if ($cat == 3)		$u_name = 'студента';
					// else if ($cat == 4)		$u_name = 'компанию';
					// else if ($cat == 5)		$u_name = 'главного менеджера';

					if ($insert)
						/*echo '
						<script type="text/javascript">
							content.message = "Вы успешно добавили '.$u_name.'!";
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
							setTimeout(function() { self.location = "/manager/staff/add.php"; }, 2000);
						</script>
						';*/
						echo '
						<script type="text/javascript">
							jQuery("#protocol").html("<span style=\"color:#53b374\">Вы успешно добавили '.$u_name.'</span>");
							setTimeout(function() { self.location = "/staff/"; }, 2000);
						</script>';
				}
			}
		}

		else if (
			($_POST['do'] == 'editUser') &&
			(isset($_POST['name']) && $api->Strings->pr($_POST['name']) != '') &&
			(isset($_POST['login']) && $_POST["login"] != '') &&
			(isset($_POST['pass']) && $_POST['pass'] != '') &&
			(isset($_POST['phone']) && $_POST['phone'] != '') &&
			(isset($_POST['cat']) && intval($_POST['cat']) != 0) &&
			(isset($_POST['edit']) && intval($_POST['edit']) != 0)
			)
		{
			$active = intval($_POST["active"]);
			$cat = intval($_POST["cat"]);
			$name = trim($api->Strings->pr($_POST["name"]));
			$login = trim($api->Strings->pr($_POST["login"]));
			$pass = trim($api->Strings->pr($_POST["pass"]));
			$phone = trim($api->Strings->pr($_POST["phone"]));
			$edit = intval($_POST["edit"]);
			
			if ($api->Managers->man_block == 2)	$cat = 3;
			
			$s=mysql_query("SELECT `id`, `id_section` FROM `i_manager_users` WHERE `login`='".$login."' AND `id`!='".$edit."' LIMIT 1");
			if (mysql_num_rows($s) == 1)
			{
				echo '
				<script type="text/javascript">
					jQuery("#error_login").html("Пользователь с таким Логином уже существует").css("display", "inline-block");
					jQuery("#login").css("border-color", "#f00");
					jQuery("#login").focus();
					jQuery(".action").show();
				</script>
				';
			}
			else
			{
				$s=mysql_query("SELECT `id`, `id_section` FROM `i_manager_users` WHERE `id`='".$edit."' LIMIT 1");
				$r=mysql_fetch_array($s);
				
				
				$sql_update = "UPDATE `i_manager_users` SET `id_section`='".$cat."', `active`='".$active."', `login`='".$login."', `pass`='".$pass."', `phone`='".$phone."', `name`='".addslashes($name)."' WHERE `id`='".$edit."'";
				$update = mysql_query($sql_update);

				if ($cat == 1)			$u_name = 'админа';
				else if ($cat == 2)		$u_name = 'преподователя';
				else if ($cat == 3)		$u_name = 'студента';
				// else if ($cat == 4)		$u_name = 'компанию';
				// else if ($cat == 5)		$u_name = 'главного менеджера';

				if ($update)
					/*echo '
					<script type="text/javascript">
						content.message = "Вы успешно сохранили '.$u_name.'!";
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
						setTimeout(function() { self.location = "/manager/staff/"; }, 50);
					</script>
					';*/
					
				echo '
				<script type="text/javascript">
					jQuery("#protocol").html("<span style=\"color:#53b374\">Вы успешно сохранили '.$u_name.'</span>");
					setTimeout(function() { self.location = "/staff/"; }, 2000);
				</script>';				
			}
		}

		else if (
			($_POST['do'] == 'deleteUser') &&
			(isset($_POST['edit']) && intval($_POST['edit']) != 0)
			)
		{			
			$id = intval($_POST["edit"]);

			$s=mysql_query("SELECT * FROM `i_manager_users` WHERE `id`>1 AND `id`='".$id."'");
			if (mysql_num_rows($s) == 1)
			{
				$r=mysql_fetch_array($s);
					
				$cat = $r["id_section"];
				
				
				$sql_delete = "DELETE FROM `i_manager_users` WHERE `id`='".$r["id"]."'";
				$delete = mysql_query($sql_delete);

				if ($cat == 1)			$u_name = 'админа';
				else if ($cat == 2)		$u_name = 'преподователя';
				else if ($cat == 3)		$u_name = 'студента';
				// else if ($cat == 4)		$u_name = 'компанию';
				// else if ($cat == 5)		$u_name = 'главного менеджера';
				/*echo '
				<script type="text/javascript">
					content.message = "Вы успешно удалили '.$u_name.'!";
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
					setTimeout(function() { self.location = "/manager/staff/"; }, 50);
				</script>
				';	*/
				echo '
				<script type="text/javascript">
					jQuery("#protocol").html("<span style=\"color:#53b374\">Вы успешно удалили '.$u_name.'</span>");
					setTimeout(function() { self.location = "/staff/"; }, 50);
				</script>';				
			}
		}
	}

	exit;
}
?>
