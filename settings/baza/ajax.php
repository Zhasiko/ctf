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
			(isset($_POST['mark']) && $_POST['mark'] != '') &&
			(isset($_POST['com_name']) && $_POST["com_name"] != '') &&
			(isset($_POST['year']) && $_POST['year'] != '')
		)
		{			
			$no_need = Array('id');
			$table = 'i_baza';
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
				$fields[$v] = str_replace('\n', '', trim(nl2br($api->Strings->pr($_POST[$v]))));
				
				$sql_field .= ($sql_field!='' ? ", " : "")."`".$v."`";
				$sql_value .= ($sql_value!='' ? ", " : "")."".($fields[$v]=='' ? "NULL" : "'".addslashes($fields[$v])."'");
			}						
			
			$s=mysql_query("SELECT `id` FROM `i_baza` WHERE `mark`='".$fields["mark"]."' AND `com_name`='".$fields["com_name"]."' AND `year`='".$fields["year"]."' LIMIT 1");
			if (mysql_num_rows($s) == 1)
			{
				echo '
				<script type="text/javascript">
					jQuery("#error_mark").html("Данная запись уже существует").css("display", "inline-block");
					jQuery("#mark").css("border-color", "#f00");
					jQuery("#com_name").css("border-color", "#f00");
					jQuery("#year").css("border-color", "#f00");
					jQuery("#mark").focus();
					jQuery(".action").show();
				</script>
				';
			}
			else
			{				
				$sql_insert = "INSERT INTO `i_baza` (".$sql_field.") VALUES (".$sql_value.")";
				$insert = mysql_query($sql_insert);				
				
				if ($insert)					
					echo '
					<script type="text/javascript">
						jQuery("#protocol").html("<span style=\"color:#53b374\">Вы успешно добавили запись</span>");
						setTimeout(function() { self.location = "/settings/baza/"; }, 2000);
					</script>';
			}			
		}

		else if (
			($_POST['do'] == 'edit') &&
			(isset($_POST['mark']) && $_POST['mark'] != '') &&
			(isset($_POST['com_name']) && $_POST["com_name"] != '') &&
			(isset($_POST['year']) && $_POST['year'] != '') &&
			(isset($_POST['edit']) && intval($_POST['edit']) != 0)
		)
		{
			$edit = intval($_POST["edit"]);
			
			$no_need = Array('id');
			$table = 'i_baza';
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
				$fields[$v] = str_replace('\n', '', trim(nl2br($api->Strings->pr($_POST[$v]))));
				
				//$sql_ .= ($sql_!='' ? ", " : "")."`".$v."`='".addslashes($fields[$v])."'";		
				$sql_ .= ($sql_!='' ? ", " : "")."`".$v."`=".($fields[$v]=='' ? "NULL" : "'".addslashes($fields[$v])."'");		
			}					
			
			$s=mysql_query("SELECT `id` FROM `i_baza` WHERE `mark`='".$fields["mark"]."' AND `com_name`='".$fields["com_name"]."' AND `year`='".$fields["year"]."' AND `id`!='".$edit."' LIMIT 1");
			if (mysql_num_rows($s) == 1)
			{
				echo '
				<script type="text/javascript">
					jQuery("#error_mark").html("Данная запись уже существует").css("display", "inline-block");
					jQuery("#mark").css("border-color", "#f00");
					jQuery("#com_name").css("border-color", "#f00");
					jQuery("#year").css("border-color", "#f00");
					jQuery("#mark").focus();
					jQuery(".action").show();
				</script>
				';
			}
			else
			{				
				$sql_update = "UPDATE `i_baza` SET ".$sql_." WHERE `id`='".$edit."'";
				$update = mysql_query($sql_update);	
				
				if ($update)						
					echo '
					<script type="text/javascript">
						jQuery("#protocol").html("<span style=\"color:#53b374\">Вы успешно сохранили запись</span>");
						setTimeout(function() { self.location = "/settings/baza/"; }, 2000);
					</script>';				
			}
		}

		else if (
			($_POST['do'] == 'delete') &&
			(isset($_POST['edit']) && intval($_POST['edit']) != 0)
			)
		{			
			$id = intval($_POST["edit"]);

			$s=mysql_query("SELECT `id` FROM `i_baza` WHERE `id`='".$id."'");
			if (mysql_num_rows($s) == 1)
			{
				$r=mysql_fetch_array($s);
									
				$sql_delete = "DELETE FROM `i_baza` WHERE `id`='".$r["id"]."'";
				$delete = mysql_query($sql_delete);
					
				echo '
				<script type="text/javascript">
					jQuery("#protocol").html("<span style=\"color:#53b374\">Вы успешно удалили запись</span>");
					setTimeout(function() { self.location = "/settings/baza/"; }, 50);
				</script>';
				
			}
		}
	}

	exit;
}
?>
