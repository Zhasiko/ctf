<?
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
		$api->Managers->man_block == 1 || $api->Managers->man_block == 2 || $api->Managers->man_block == 5
	)
	{
		if (
			($_POST['do'] == 'add') &&
			(isset($_POST['name_temp']) && $_POST['name_temp'] != '') &&
			(isset($_POST['name']) && $_POST["name"] != '') &&
			(isset($_POST['adres']) && $_POST['adres'] != '')
		)
		{			
			$no_need = Array('id');
			$table = 'i_temp_pdf';
			$query = "SHOW COLUMNS FROM $table";
			if ($output = mysql_query($query)):
				$columns = array();
				while($result = mysql_fetch_assoc($output)):
					if (!in_array($result['Field'], $no_need))
						$columns[] = $result['Field'];
				endwhile;
			endif;

			$fields = Array(); $sql_field = ""; $sql_value = "";
			foreach($columns as $k=>$v)
			{
				$fields[$v] = trim($api->Strings->pr($_POST[$v]));	
				
				$sql_field .= ($sql_field!='' ? ", " : "")."`".$v."`";
				$sql_value .= ($sql_value!='' ? ", " : "")."".($fields[$v]=='' ? "NULL" : "'".addslashes($fields[$v])."'");
			}						
			
						
			$sql_insert = "INSERT INTO `".$table."` (".$sql_field.") VALUES (".$sql_value.")";
			$insert = mysql_query($sql_insert);				

			if ($insert)					
				echo '
				<script type="text/javascript">
					jQuery("#protocol").html("<span style=\"color:#53b374\">Вы успешно добавили шаблон</span>");
					setTimeout(function() { self.location = "/settings/temp_pdf/"; }, 2000);
				</script>';					
		}

		else if (
			($_POST['do'] == 'edit') &&
			(isset($_POST['name_temp']) && $_POST['name_temp'] != '') &&
			(isset($_POST['name']) && $_POST["name"] != '') &&
			(isset($_POST['adres']) && $_POST['adres'] != '') &&
			(isset($_POST['edit']) && intval($_POST['edit']) != 0)
		)
		{
			$edit = intval($_POST["edit"]);
			
			$no_need = Array('id');
			$table = 'i_temp_pdf';
			$query = "SHOW COLUMNS FROM $table";
			if ($output = mysql_query($query)):
				$columns = array();
				while($result = mysql_fetch_assoc($output)):
					if (!in_array($result['Field'], $no_need))
						$columns[] = $result['Field'];
				endwhile;
			endif;

			$fields = Array(); $sql_ = "";
			foreach($columns as $k=>$v)
			{
				$fields[$v] = trim($api->Strings->pr($_POST[$v]));	
				
				$sql_ .= ($sql_!='' ? ", " : "")."`".$v."`='".addslashes($fields[$v])."'";				
			}					
			
					
			$sql_update = "UPDATE `".$table."` SET ".$sql_." WHERE `id`='".$edit."'";
			$update = mysql_query($sql_update);				

			if ($update)						
				echo '
				<script type="text/javascript">
					jQuery("#protocol").html("<span style=\"color:#53b374\">Вы успешно сохранили шаблон</span>");
					setTimeout(function() { self.location = "/settings/temp_pdf/"; }, 2000);
				</script>';							
		}

		else if (
			($_POST['do'] == 'delete') &&
			(isset($_POST['edit']) && intval($_POST['edit']) != 0)
			)
		{			
			$id = intval($_POST["edit"]);

			$s=mysql_query("SELECT `id` FROM `i_temp_pdf` WHERE `id`='".$id."'");
			if (mysql_num_rows($s) == 1)
			{
				$r=mysql_fetch_array($s);
									
				$sql_delete = "DELETE FROM `i_temp_pdf` WHERE `id`='".$r["id"]."'";
				$delete = mysql_query($sql_delete);
					
				echo '
				<script type="text/javascript">
					jQuery("#protocol").html("<span style=\"color:#53b374\">Вы успешно удалили шаблон</span>");
					setTimeout(function() { self.location = "/settings/temp_pdf/"; }, 50);
				</script>';
				
			}
		}
	}

	exit;
}
?>
