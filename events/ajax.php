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
		$api->Managers->man_block == 1 || $api->Managers->man_block == 2
	)
	{
        
		if (
			($_POST['do'] == 'addEvent') &&
			(isset($_POST['name']) && $api->Strings->pr($_POST['name']) != '') &&
			(isset($_POST['link']) && $_POST["link"] != '') &&
            (isset($_POST['description']) && $_POST['description'] != '') &&
			(isset($_POST['date']) && $_POST['date'] != '')
		)
		{
			$active = intval($_POST["active"]);			
			$name = trim($api->Strings->pr($_POST["name"]));
			$link = trim($api->Strings->pr($_POST["link"]));
			$date = trim($api->Strings->pr($_POST["date"]));
            $description = trim($api->Strings->pr($_POST["description"]));
            
            
			$date = DateTime::createFromFormat('d-m-Y H:i', $_POST["date"]);
			$formatted_date = $date->format('Y-m-d H:i:s');

			if ($api->Managers->man_block == 1 || $api->Managers->man_block == 2)
			{
                    
					$sql_insert = "INSERT INTO `i_events` (`name`, `description`,`link`, `active`, `date`) VALUES ('".$name."', '".$description."', '".$link."', '".$active."', '".$formatted_date."')";
					$insert = mysql_query($sql_insert);
					
					if ($insert)
						echo '
						<script type="text/javascript">
							jQuery("#protocol").html("<span style=\"color:#53b374\">Вы успешно добавили событие</span>");
							setTimeout(function() { self.location = "/events/"; }, 2000);
						</script>';
				
			}
		}

		else if (
			($_POST['do'] == 'editEvent') &&
			(isset($_POST['name']) && $api->Strings->pr($_POST['name']) != '') &&
			(isset($_POST['link']) && $_POST["link"] != '') &&
            (isset($_POST['description']) && $_POST['description'] != '') &&
			(isset($_POST['date']) && $_POST['date'] != '') &&
            (isset($_POST['edit']) && intval($_POST['edit']) != 0)
			)
		{
			$active = intval($_POST["active"]);			
			$name = trim($api->Strings->pr($_POST["name"]));
			$link = trim($api->Strings->pr($_POST["link"]));
			$date = trim($api->Strings->pr($_POST["date"]));
            $description = trim($api->Strings->pr($_POST["description"]));
            $edit = intval($_POST["edit"]);
			
			            
			$date = DateTime::createFromFormat('d-m-Y H:i', $_POST["date"]);
			// $formatted_date = $date->format('Y-m-d H:i:s');
        
			if ($date) {
				$formatted_date = $date->format('Y-m-d H:i:s');
				
			} else {
				$formatted_date = $_POST["date"];
			}

            $s=mysql_query("SELECT `id` FROM `i_events` WHERE `id`='".$edit."' LIMIT 1");
            $r=mysql_fetch_array($s);
            
            
            $sql_update = "UPDATE `i_events` SET `name`='".$name."', `active`='".$active."', `link`='".$link."', `description`='".$description."', `date`='".$formatted_date."' WHERE `id`='".$edit."'";
            $update = mysql_query($sql_update);

        
            echo '
            <script type="text/javascript">
                jQuery("#protocol").html("<span style=\"color:#53b374\">Вы успешно сохранили событие</span>");
                setTimeout(function() { self.location = "/events/"; }, 2000);
            </script>';				
        
		}

		else if (
			($_POST['do'] == 'deleteEvent') &&
			(isset($_POST['edit']) && intval($_POST['edit']) != 0)
			)
		{			
			$id = intval($_POST["edit"]);

			$s=mysql_query("SELECT * FROM `i_events` WHERE `id`='".$id."'");
			if (mysql_num_rows($s) == 1)
			{
				$r=mysql_fetch_array($s);
					
				
				
				$sql_delete = "DELETE FROM `i_events` WHERE `id`='".$r["id"]."'";
				$delete = mysql_query($sql_delete);

				
				echo '
				<script type="text/javascript">
					jQuery("#protocol").html("<span style=\"color:#53b374\">Вы успешно удалили событие</span>");
					setTimeout(function() { self.location = "/events/"; }, 50);
				</script>';				
			}
		}
	}

	exit;
}
?>
