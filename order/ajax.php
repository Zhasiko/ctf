<?
//ini_set('error_reporting', E_ALL);
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
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
		(
			$api->Managers->man_block == 1 || // админ 
			$api->Managers->man_block == 2 || $api->Managers->man_block == 5 || // менеджеры
			$api->Managers->man_block == 3 || // досмотрщик
			$api->Managers->man_block == 4 // брокер
		)
	)
	{
		$no_need = Array('id', 'create_date', 'id_man', 'id_exam', 'id_broker', 'link_man', 'link_man2', 'pdf_num', 'pdf_seriya', 'pdf_file', 'excel_file', 'date_edit_exam', 'status', 'status2', 'id_temp', 'name_temp', 'info_temp', 'd_temp', 'date_oform', 'pdf_file_client', 'hash');
		
		if (
			($_POST['do'] == 'chooseCar')  &&
			(isset($_POST['field']) && ($_POST['field'] == 'car_type' || $_POST['field'] == 'mark' || $_POST['field'] == 'com_name' || $_POST['field'] == 'year' || $_POST['field'] == 'volume'))
		)
		{
			// досмотрщик не может
			if ($api->Managers->man_block != 3)
			{
				$fields = Array();

				$field = trim($api->Strings->pr($_POST["field"]));	
				$fields["car_type"] = trim($api->Strings->pr($_POST["car_type"]));	
				$fields["mark"] = trim($api->Strings->pr($_POST["mark"]));	
				$fields["com_name"] = trim($api->Strings->pr($_POST["com_name"]));	
				$fields["year"] = trim($api->Strings->pr($_POST["year"]));	
				$fields["volume"] = trim($api->Strings->pr($_POST["volume"]));	

				$java_mark = '
				jQuery("#mark option").remove();
				jQuery("#mark").append("<option value=\"\">сперва выберите Тип автомобиля </option>");';

				$java_com_name = '
				jQuery("#com_name option").remove();
				jQuery("#com_name").append("<option value=\"\">сперва выберите Марку</option>");';
				
				$java_year = '
				jQuery("#year option").remove();
				jQuery("#year").append("<option value=\"\">сперва выберите Коммерческое наименование </option>");';

				$java_volume = '
				jQuery("#volume option").remove();
				jQuery("#volume").append("<option value=\"\">сперва выберите Год выпуска </option>");';

				$info = Array();

				$java = '';
				if ($field == 'car_type')
				{
					$info["field"] = 'mark';
					$info["opt_name"] = 'Марку';
					$info["sql"] = "`car_type`='".$fields["car_type"]."'";
					$info["java_no"] = $java_mark.$java_com_name.$java_year.$java_volume;
				}
				else if ($field == 'mark')
				{
					$info["field"] = 'com_name';
					$info["opt_name"] = 'Коммерческое наименование';
					$info["sql"] = "`car_type`='".$fields["car_type"]."' AND `mark`='".$fields["mark"]."'";				
					$info["java_no"] = $java_com_name.$java_year.$java_volume;												
				}
				else if ($field == 'com_name')
				{
					$info["field"] = 'year';
					$info["opt_name"] = 'Год выпуска';			
					$info["sql"] = "`car_type`='".$fields["car_type"]."' AND `mark`='".$fields["mark"]."' AND `com_name`='".$fields["com_name"]."'";
					$info["java_no"] = $java_year.$java_volume;												
				}
				else if ($field == 'year')
				{
					$info["field"] = 'volume';
					$info["opt_name"] = 'Объём';	
					$info["sql"] = "`car_type`='".$fields["car_type"]."' AND `mark`='".$fields["mark"]."' AND `com_name`='".$fields["com_name"]."' AND `year`='".$fields["year"]."'";
					$info["java_no"] = $java_volume;					
				}

				$java .= $info["java_no"];
				if ($fields[$field]!='' && sizeof($info) > 0)
				{	
					/*
					$java .= '
					jQuery("#'.$info["field"].' option").remove();
					jQuery("#'.$info["field"].'").append("<option value=\"\">выберите '.$info["opt_name"].'</option>");';
					*/					

					$s=mysql_query("SELECT `".$info["field"]."` FROM `i_baza` WHERE ".$info["sql"]." GROUP BY `".$info["field"]."` ORDER BY `".$info["field"]."` ASC");
					if (mysql_num_rows($s) > 0)
					{										
						while($r=mysql_fetch_array($s))
						{
							$java .= 'jQuery("#'.$info["field"].'").append("<option value=\"'.stripslashes($r[$info["field"]]).'\">'.stripslashes($r[$info["field"]]).'</option>");';
						}

						$java .= 'jQuery("#'.$info["field"].'").prop("disabled", false);';
					}
					else				
						$java .= '					
						jQuery("#'.$info["field"].'").prop("disabled", false);';
				}									
				
				$no_need[] = 'car_type';
				$no_need[] = 'mark';
				$no_need[] = 'com_name';
				$no_need[] = 'year';
				$no_need[] = 'volume';
				$no_need[] = 'vin';				
				$no_need[] = 'user_name';
				$no_need[] = 'user_uyr_adres';
				$no_need[] = 'user_fac_adres';
				$no_need[] = 'user_phone';
				$no_need[] = 'user_fax';
				$no_need[] = 'user_mail';
				$no_need[] = 'broker_name';
				$no_need[] = 'date_issue';

				$table = 'i_baza';
				$query = "SHOW COLUMNS FROM $table";
				if ($output = mysql_query($query)):
					$columns = array();
					while($result = mysql_fetch_assoc($output)):
						if (!in_array($result['Field'], $no_need))
							$columns[] = $result['Field'];
					endwhile;
				endif;						

				if ($field == 'volume')
				{				
					$s=mysql_query("SELECT * FROM `i_baza` WHERE `car_type`='".$fields["car_type"]."' AND `mark`='".$fields["mark"]."' AND `com_name`='".$fields["com_name"]."' AND `year`='".$fields["year"]."' AND `volume`='".$fields["volume"]."' LIMIT 1");
					if (mysql_num_rows($s) > 0)
					{										
						$r=mysql_fetch_array($s);
						
						foreach($columns as $k=>$v)			
						{
							$value_baza = str_replace('\\', '', str_replace('\n', '', nl2br(stripslashes($r[$v]))));
							
							$java .= 'jQuery("#'.$v.'").val("'.$value_baza.'");';
						}
					}
					else
					{
						foreach($columns as $k=>$v)			
							$java .= 'jQuery("#'.$v.'").val("");';
					}
				}	
				else
				{
					foreach($columns as $k=>$v)			
						$java .= 'jQuery("#'.$v.'").val("");';
				}

				if ($java != '')
					echo '
					<script type="text/javascript">
						'.$java.'
					</script>';	
			}
		}
		
		else if (

			($_POST['do'] == 'add') &&
			(isset($_POST['task_name']) && $_POST['task_name'] != '') &&
			(isset($_POST['task_type']) && $_POST['task_type'] != '') &&
			(isset($_POST['level']) && $_POST['level'] != '') &&
			(isset($_POST['link']) && $_POST['link'] != '') &&
			(isset($_POST['description']) && $_POST['description'] != '') &&
			(isset($_POST['flag']) && $_POST['flag'] != '') &&
			(isset($_POST['points']) && $_POST['points'] != '') &&
			(isset($_POST['solving_avg']) && $_POST['solving_avg'] != '')

		)
		{		
			// только брокер
			print_r($_POST);
			if (
				($api->Managers->man_block == 4) ||
				(
					($api->Managers->man_block == 1 || $api->Managers->man_block == 2 || $api->Managers->man_block == 5)
				)
			)
			{
				$table = 'i_order';
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
					$fields[$v] = str_replace('\\', '', str_replace('\n', '', trim(nl2br($api->Strings->pr($_POST[$v])))));
					
						$fields[$v] = trim($api->Strings->pr($_POST[$v]));	
						
					$sql_field .= ($sql_field!='' ? ", " : "")."`".$v."`";
					$sql_value .= ($sql_value!='' ? ", " : "")."".($fields[$v]=='' ? "NULL" : "'".addslashes($fields[$v])."'");
				}						
				
				
				$sql_insert = "INSERT INTO `i_order` (`create_date`,".$sql_field.") VALUES ('".date("Y-m-d H:i:s")."', ".$sql_value.")";
				
				$insert = mysql_query($sql_insert);					
				$id_order = mysql_insert_id();

				
				
				if ($insert)					
					echo '
					<script type="text/javascript">
						jQuery(".protocol_add").html("<span style=\"color:#53b374\">Вы успешно добавили задачу</span>");
						//setTimeout(function() { self.location = "/order/"; }, 2000);
					</script>';		
			}
			else
				echo '
				<script type="text/javascript">
					jQuery(".protocol_add").html("<span style=\"color:#f00\">Ошибка добавления завяки, без Компании</span>");					
				</script>';	
		}

		else if (
			($_POST['do'] == 'edit') &&
			(isset($_POST['user_name']) && $_POST['user_name'] != '') &&
			(isset($_POST['user_iin']) && $_POST['user_iin'] != '') &&
			(isset($_POST['user_phone']) && $_POST['user_phone'] != '') &&
			(isset($_POST['id_broker']) && intval($_POST['id_broker']) > 0) &&
			(isset($_POST['vin']) && $_POST['vin'] != '') &&
			(
				(isset($_POST['mark']) && $_POST['mark'] != '') ||
				(isset($_POST['mark_input]']) && $_POST['mark_input'] != '')
			) &&
			(
				(isset($_POST['com_name']) && $_POST["com_name"] != '') ||
				(isset($_POST['com_name_input']) && $_POST["com_name_input"] != '') 
			) &&
			(
				(isset($_POST['year']) && $_POST['year'] != '') ||
				(isset($_POST['year_input']) && $_POST['year_input'] != '') 
			) &&
			(
				(isset($_POST['volume']) && $_POST['volume'] != '') ||
				(isset($_POST['volume_input']) && $_POST['volume_input'] != '') 
			) &&
			(isset($_POST['edit']) && intval($_POST['edit']) != 0)
		)
		{
			if ($api->Managers->man_block == 1 || $api->Managers->man_block == 2 || $api->Managers->man_block == 5)
			{			
				$edit = intval($_POST["edit"]);

				$table = 'i_order';
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
					$fields[$v] = str_replace('\\', '', str_replace('\n', '', trim(nl2br($api->Strings->pr($_POST[$v])))));
					
					if (
						($v=='mark' || $v=='com_name' || $v=='year' || $v=='volume') &&
						(trim($api->Strings->pr($_POST[$v])) == 'прочее')
					)
						$fields[$v] = trim($api->Strings->pr($_POST[$v."_input"]));

					//$sql_ .= ($sql_!='' ? ", " : "")."`".$v."`='".addslashes($fields[$v])."'";	
					$sql_ .= ($sql_!='' ? ", " : "")."`".$v."`=".($fields[$v]=='' ? "NULL" : "'".addslashes($fields[$v])."'");					
				}																
				
				$s = mysql_query("SELECT `pdf_file`, `excel_file`, `pdf_file_client` FROM `i_order` WHERE `id`='".$edit."' LIMIT 1");								
				$r = mysql_fetch_array($s);	
				
				$pdf_file = $r["pdf_file"];
				$excel_file = $r["excel_file"];
				$pdf_file = $r["pdf_file_client"];
				
				// удалить файл ============
				if ($pdf_file!='')
				{
					$dirFile = $_SERVER['DOCUMENT_ROOT'].'/upload/order/'.$pdf_file;
					if (is_file($dirFile)) { unlink($dirFile); }
				}	
				
				// удалить excel ============
				if ($excel_file!='')
				{
					$dirFile = $_SERVER['DOCUMENT_ROOT'].'/upload/order/'.$excel_file;
					if (is_file($dirFile)) { unlink($dirFile); }
				}	
				
				if ($pdf_file_client!='')
				{
					$dirFile = $_SERVER['DOCUMENT_ROOT'].'/upload/client_pdf/'.$pdf_file_client;
					if (is_file($dirFile)) { unlink($dirFile); }
				}
				
				$sql__ = "";
				if ($api->Managers->man_block == 1 || $api->Managers->man_block == 5)			
					$sql__ = ", `id_broker`='".intval($_POST['id_broker'])."'";
				
				$sql_update = "UPDATE `i_order` SET ".$sql_.", `pdf_file`='', `excel_file`=''".$sql__." WHERE `id`='".$edit."'";
				$update = mysql_query($sql_update);		
				
				//echo '<div style="display:none">'.$sql_update.' -- '.mysql_error().'</div>';

				if ($update)						
					echo '
					<script type="text/javascript">
						jQuery(".protocol_add").html("<span style=\"color:#53b374\">Вы успешно обновили заявку</span>");
						setTimeout(function() { self.location = "/order/more.php?edit='.$edit.'"; }, 2000);
					</script>';
			}			
		}

		else if (
			($_POST['do'] == 'delete') &&
			(isset($_POST['edit']) && intval($_POST['edit']) != 0)
			)
		{			
			if ($api->Managers->man_block == 1 || $api->Managers->man_block == 5)
			{
				$id = intval($_POST["edit"]);

				$s=mysql_query("SELECT `id`, `status` FROM `i_order` WHERE `id`='".$id."'");
				if (mysql_num_rows($s) == 1)
				{
					$r=mysql_fetch_array($s);
					
					if (intval($r["status"]) == 0)											
					{
						include_once($_SERVER["DOCUMENT_ROOT"].'/basket/logs.php');
						
						$table = 'i_order';						
						$id_log = $logs->insertToLogs($table, $id, $api->Managers->man_id);
						
						if ($id_log > 0)
						{
							$sql_delete = "DELETE FROM `i_order` WHERE `id`='".$r["id"]."'";
							$delete = mysql_query($sql_delete);

							echo '
							<script type="text/javascript">
								jQuery("#protocolDel").html("<span style=\"color:#53b374\">Вы успешно удалили заявку</span>");
								setTimeout(function() { self.location = "/order/"; }, 50);
							</script>';
						}
						else
							echo '
							<script type="text/javascript">
								jQuery("#protocolDel").html("<span style=\"color:#f00\">Ошибка удаления!</span>");								
							</script>';
					}
				}
			}
		}
		
		// ИЗМЕНИТЬ СТАТУС в more.php ================
		else if (
			($_POST['do'] == 'saveStatus') &&
			(isset($_POST['status']) && (intval($_POST['status']) >= 0 && intval($_POST['status']) <= 3)) &&
			(isset($_POST['id']) && intval($_POST['id']) != 0)
		)
		{	
			if ($api->Managers->man_block == 1 || $api->Managers->man_block == 2 || $api->Managers->man_block == 5)
			{
				$status = intval($_POST['status']);
				$id = intval($_POST['id']);			

				$s=mysql_query("SELECT * FROM `i_order` WHERE `id`='".$id."' LIMIT 1");
				if (mysql_num_rows($s) > 0)
				{ 
					$r=mysql_fetch_array($s);	
					
					$id_man = intval($r["id_man"]);
					
					$sql_ = ""; $can_up = 1; $error = '';
					if ($status == 0)			
					{
						if ($api->Managers->man_block == 1 || $api->Managers->man_block == 5)
							$sql_ = ", `id_man`=0, `status2`='0'";
						else
						{
							$can_up = 0; 
							$error .= '<br />У Вас нет прав!';
						}
					}
					else if ($status == 1)						$sql_ = ", `id_man`=0, `status2`='0'";
					else if ($status == 2)		
					{
						if ($api->Managers->man_block == 1 || $api->Managers->man_block == 5)
							$sql_ = "";
						else if (
							($api->Managers->man_block == 2) &&
							($id_man == 0 || $id_man == $api->Managers->man_id)
						)
							$sql_ = ", `id_man`='".$api->Managers->man_id."', `status2`='0'";
						else
						{
							$can_up = 0; 
							$error .= '<br />Заявку уже взял другой менеджер!';
						}
					}
					else if ($status == 3)
					{
						if ($r["link_man"] == '')	
						{
							$can_up = 0; 
							$error .= '<br />Не заполнено поле Ссылка №1 на сайт у менеджера
							<script type="text/javascript">
								jQuery("#link_man").css("border-color", "#f00").focus();
							</script>
							';
						}
						else if ($r["link_man2"] == '')	
						{
							$can_up = 0; 
							$error .= '<br />Не заполнено поле Ссылка №2 на сайт у менеджера
							<script type="text/javascript">
								jQuery("#link_man2").css("border-color", "#f00").focus();
							</script>
							';
						}
						else
						{
							$foto_name = Array();
							$foto_name[1] = 'ВИД СПЕРЕДИ';
							$foto_name[2] = 'ВИД СПРАВА';
							$foto_name[3] = 'ВИД СЗАДИ';
							$foto_name[4] = 'ВИД СЛЕВА';
							$foto_name[5] = 'ШИЛЬДИК (БИРКА С ВИН И ДАТОЙ ВЫПУСКА)';
							$foto_name[6] = 'БИРКА НА РЕМНЕ БЕЗОПАСНОСТИ';
							$foto_name[7] = 'МАРКА И НОМЕР ДВИГАТЕЛЯ';
							$foto_name[8] = 'ФОТО РАСПОЛОЖЕНИЯ ДВИГАТЕЛЯ';
							$foto_name[9] = 'ФОТО ГЛУШИТЕЛЕЙ';
							$foto_name[10] = 'ФОТО СЕЛЕКТОРА КОРОБКИ ПЕРЕДАЧ И СТОЯНОЧНОГО ТОРМОЗА';
							$foto_name[11] = 'ФОТО ШИН';
							$foto_name[12] = 'ФОТО САЛОНА (КОЛИЧЕСТВО МЕСТ)';
							
							$sF = mysql_query("SELECT * FROM `i_foto` WHERE `id_order`='".$id."' LIMIT 1");
							if (mysql_num_rows($sF) > 0)
							{
								$rF = mysql_fetch_array($sF);
								
								$no_foto = '';
								for($i=1; $i<=12; $i++)
								{
									$dirFile = $_SERVER['DOCUMENT_ROOT'].'/upload/foto/'.$rF["foto_".$i];							
									
									if ($rF["foto_".$i]=='' || !is_file($dirFile))
										$no_foto .= ($no_foto!='' ? ', ' : '').$foto_name[$i];
										
								}
								
								if ($no_foto != '')
								{
									$can_up = 0; 
									$error .= '<br />Досмотрщик не загрузил фото: '.$no_foto;
								}
								
								if ($rF["link_video"] == '')
								{
									$can_up = 0; 
									$error .= '<br />Досмотрщик не указал ссылку на видео';
								}																	
							}
						}
						
						$sql_ = ", `status2`='1'";
					}
					
					if ($can_up == 1)
					{
						$sql_update = "UPDATE `i_order` SET `status`='".$status."'".$sql_." WHERE `id`='".$id."'";
						$update = mysql_query($sql_update);	

						if ($update)				
							echo '
							<span style="color:#53b374;">Вы успешно изменили статус заявки!</span>
							<script type="text/javascript">
								setTimeout(function() { self.location = "/order/more.php?edit='.$id.'"; }, 1500);
							</script>
							';
					}
					else
						echo '<span style="color:#f00;">Вы не можете измениить статус!'.$error.'</span>';
				}
			}
		}
		
		// ИЗМЕНИТЬ СТАТУС №2 в more.php ================
		else if (
			($_POST['do'] == 'saveStatus2') &&
			(isset($_POST['status']) && (intval($_POST['status']) == 1 || intval($_POST['status']) == 3)) &&
			(isset($_POST['id']) && intval($_POST['id']) != 0)
		)
		{	
			if ($api->Managers->man_block == 1 || $api->Managers->man_block == 2 || $api->Managers->man_block == 5)
			{
				$status = intval($_POST['status']);
				$id = intval($_POST['id']);			

				$s=mysql_query("SELECT * FROM `i_order` WHERE `id`='".$id."' LIMIT 1");
				if (mysql_num_rows($s) > 0)
				{ 
					$r=mysql_fetch_array($s);	
				
					if (
						($status == 1) ||
						($status == 3 && intval($r["status"]) == 3)
					)
					{
						$sql_update = "UPDATE `i_order` SET `status2`='".$status."' WHERE `id`='".$id."'";
						$update = mysql_query($sql_update);	

						if ($update)				
							echo '
							<span style="color:#53b374;">Вы успешно изменили статус №2 заявки!</span>
							<script type="text/javascript">
								setTimeout(function() { self.location = "/order/more.php?edit='.$id.'"; }, 1500);
							</script>
							';
					}
					else
						echo '<span style="color:#f00;">Вы не можете измениить статус №2, пока статус №1 не будет Готов</span>';
				}
			}
		}
		
		// СОХРАНИТЬ ДАТУ ВЫПИСКИ в more.php =============
		else if (
			($_POST['do'] == 'saveDateI') &&
			(isset($_POST['date_issue'])) &&			
			(isset($_POST['id']) && intval($_POST['id']) != 0)
		)
		{	
			if ($api->Managers->man_block == 1 || $api->Managers->man_block == 2 || $api->Managers->man_block == 5)
			{
				$date_issue = trim($api->Strings->pr($_POST["date_issue"]));					
				$id = intval($_POST['id']);			

				$s=mysql_query("SELECT * FROM `i_order` WHERE `id`='".$id."' LIMIT 1");
				if (mysql_num_rows($s) > 0)
				{ 
					$r=mysql_fetch_array($s);	
					
					if ($date_issue == '')
						$sql_update = "UPDATE `i_order` SET `date_issue`=NULL WHERE `id`='".$id."'";
					else
						$sql_update = "UPDATE `i_order` SET `date_issue`='".$date_issue."' WHERE `id`='".$id."'";
					
					$update = mysql_query($sql_update);	

					if ($update)				
						echo '
						<span style="color:#53b374;">Вы успешно сохранили Дату выпуска</span>						
						';
				}
			}
		}
		
		// СОХРАНИТЬ ССЫЛКИ в more.php =================
		else if (
			($_POST['do'] == 'saveFields') &&
			(isset($_POST['link_man'])) &&
			(isset($_POST['link_man2'])) &&
			(isset($_POST['id']) && intval($_POST['id']) != 0)
		)
		{	
			if ($api->Managers->man_block == 1 || $api->Managers->man_block == 2 || $api->Managers->man_block == 5)
			{
				$link_man = trim($api->Strings->pr($_POST["link_man"]));	
				$link_man2 = trim($api->Strings->pr($_POST["link_man2"]));					
				$id = intval($_POST['id']);			

				$s=mysql_query("SELECT * FROM `i_order` WHERE `id`='".$id."' LIMIT 1");
				if (mysql_num_rows($s) > 0)
				{ 
					$r=mysql_fetch_array($s);	
					
					$sql_ = "`link_man`='".$link_man."', `link_man2`='".$link_man2."', `status`=2";
					
					$sql_update = "UPDATE `i_order` SET ".$sql_." WHERE `id`='".$id."'";
					$update = mysql_query($sql_update);	

					if ($update)				
						echo '
						<span style="color:#53b374;">Вы успешно изменили данные!</span>
						<script type="text/javascript">
							jQuery("#pdf_block").hide();
							jQuery("#status option[value=2]").prop("selected", true);
						</script>
						';
				}
			}
		}
		
		// УДАЛИТЬ ФОТО ЗАЯВКИ =================
		else if (
			($_POST['do'] == 'deleteFoto') &&
			isset($_POST['type']) && (intval($_POST['type']) >= 1 && intval($_POST['type']) <= 12) &&
			isset($_POST['id']) && (intval($_POST['id']) != 0)
		)
		{					
			$type = intval($_POST['type']);
			$id = intval($_POST['id']);			
			
			if ($api->Managers->man_block == 3)
			{			
				$sql_wh = " AND ( ( (`status`=1 OR `status`=2) AND (`id_exam`='".$api->Managers->man_id."' OR `id_exam`=0 OR `id_exam` IS NULL) ) OR ( (`status`=3 OR `status`=4) AND `id_exam`='".$api->Managers->man_id."') )";

				$s = mysql_query("SELECT * FROM `i_order` WHERE `id`='".$id."'".$sql_wh." LIMIT 1");
				if (mysql_num_rows($s) > 0)
				{					
					$r=mysql_fetch_array($s);
					
					$status = intval($r["status"]);
					
					$sF = mysql_query("SELECT * FROM `i_foto` WHERE `id_order`='".$id."' LIMIT 1");
					if (mysql_num_rows($sF) > 0)
					{
						$rF = mysql_fetch_array($sF);
						$id_foto = intval($rF["id"]);
						$foto = $rF["foto_".$type];
					
						// удалить файл ============
						if ($foto!='')
						{
							$dirFile = $_SERVER['DOCUMENT_ROOT'].'/upload/foto/'.$foto;
							if (is_file($dirFile)) { unlink($dirFile); }
						}										

						$sql = "UPDATE `i_foto` SET `foto_".$type."`='' WHERE `id`='".$id_foto."'";
						$update = mysql_query($sql);	
						
						// если статус Готова, нужно изменить
						if ($status == 3)
						{
							$sql_update = "UPDATE `i_order` SET `status`='4' WHERE `id`='".$id."'";
							$update_order = mysql_query($sql_update);	
						}
						
						if ($update)
							echo '
							<script type="text/javascript">			
								jQuery("#add_foto'.$type.'").show();
								jQuery("#delete_foto'.$type.'").hide();
								jQuery("#load_to_foto'.$type.'").html("");										
							</script>
							';	
					}
				}
			}
		}
		
		// СОХРАНИТЬ ССЫЛКУ НА ВИДЕО в more.php =====================
		else if (
			($_POST['do'] == 'saveVideo') &&
			(isset($_POST['link_video'])) &&			
			(isset($_POST['id']) && intval($_POST['id']) != 0)
		)
		{	
			if ($api->Managers->man_block == 3)
			{
				$link_video = trim($api->Strings->pr($_POST["link_video"]));					
				$id = intval($_POST['id']);			

				$sql_wh = " AND ( ( (`status`=1 OR `status`=2) AND (`id_exam`='".$api->Managers->man_id."' OR `id_exam`=0 OR `id_exam` IS NULL) ) OR ( (`status`=3 OR `status`=4) AND `id_exam`='".$api->Managers->man_id."') )";

				$s = mysql_query("SELECT * FROM `i_order` WHERE `id`='".$id."'".$sql_wh." LIMIT 1");
				if (mysql_num_rows($s) > 0)
				{					
					$r=mysql_fetch_array($s);
					
					$sF = mysql_query("SELECT * FROM `i_foto` WHERE `id_order`='".$id."' LIMIT 1");
					if (mysql_num_rows($sF) > 0)
					{
						$rF = mysql_fetch_array($sF);						
						$id_foto = intval($rF["id"]);																
					
						$sql_update = "UPDATE `i_foto` SET `link_video`='".$link_video."' WHERE `id`='".$id_foto."'";
						$update = mysql_query($sql_update);	

						if ($update)				
							echo '
							<span style="color:#53b374;">Вы успешно изменили данные!</span>
							';
					}
					else
					{
						$sql_insert = "INSERT INTO `i_foto` (`id_order`, `link_video`) VALUES ('".$id."', '".$link_video."')";
						$insert = mysql_query($sql_insert);	
						
						$sql_update = "UPDATE `i_order` SET `id_exam`='".$api->Managers->man_id."' WHERE `id`='".$id."'";
						$update_order = mysql_query($sql_update);
						
						if ($insert)				
							echo '
							<span style="color:#53b374;">Вы успешно изменили данные!</span>
							';
					}
				}
			}
		}
		
		// СКАЧАТЬ ФАЙЛ PDF или EXCEL в more.php ==================
		else if (
			($_POST['do'] == 'getFile') &&
			(isset($_POST['file']) && ($_POST['file'] == 'PDF' || $_POST['file'] == 'EXCEL')) &&
			(isset($_POST['pdf_num']) && $_POST['pdf_num'] != '') &&
			(isset($_POST['pdf_seriya']) && $_POST['pdf_seriya'] != '') &&
			(isset($_POST['date_oform']) && $_POST['date_oform'] != '') &&
			(isset($_POST['id_temp']) && intval($_POST['id_temp']) != 0) &&
			(isset($_POST['id']) && intval($_POST['id']) != 0)
		)
		{	
			if ($api->Managers->man_block == 1 || $api->Managers->man_block == 2 || $api->Managers->man_block == 5)
			{
				$pdf_num = trim($api->Strings->pr($_POST["pdf_num"]));	
				$pdf_seriya = trim($api->Strings->pr($_POST["pdf_seriya"]));		
				$date_oform = trim($api->Strings->pr($_POST["date_oform"]));		
				$id_temp = intval($_POST['id_temp']);			
				$id = intval($_POST['id']);			

				$s=mysql_query("SELECT * FROM `i_order` WHERE `id`='".$id."' AND `status`=3 LIMIT 1");
				if (mysql_num_rows($s) > 0)
				{ 
					$r=mysql_fetch_array($s);	
															
					$sql_ = "`pdf_num`='".$pdf_num."', `pdf_seriya`='".$pdf_seriya."', `id_temp`='".$id_temp."', `date_oform`='".$date_oform."'";
					
					$director = 'Баратов Р. Х.'; $info_temp = ''; $name_temp = '';
					$sT=mysql_query("SELECT * FROM `i_temp_pdf` WHERE `id`='".$id_temp."' LIMIT 1");
					if (mysql_num_rows($sT) > 0)
					{
						$rT=mysql_fetch_array($sT);
						
						$info_temp = $rT["name"].', '.$rT["adres"].($rT["phone"]!='' ? ', тел/факс: '.$rT["phone"] : '').($rT["mail"]!='' ? ', '.$rT["mail"] : '').', № '.$rT["number"].' до '.$rT["date_do"];
						$director = stripslashes($rT["director"]);
												
						$sql_ .= ", `info_temp`='".$info_temp."', `d_temp`='".$rT["director"]."'";
						
						$info_temp = $api->Strings->pr_plus(htmlspecialchars_decode(stripslashes($info_temp)));
					}
					
					$dirFile = $_SERVER['DOCUMENT_ROOT'].'/upload/order/'.$r["pdf_file"];
					if (is_file($dirFile)) { unlink($dirFile); }
					
					$dirFileExcel = $_SERVER['DOCUMENT_ROOT'].'/upload/order/'.$r["excel_file"];					
					if (is_file($dirFileExcel)) { unlink($dirFileExcel); }
					
					$name_file_pdf = ''; $name_file_excel = '';
					
					if ($_POST['file'] == 'PDF')
					{
						//$name_file_pdf = 'order_'.$id.'_'.date("YmdHis").'.pdf';
						//$nn_f = str_replace(' ', '_', ($r["user_name"].'_'.$r["mark"].'_'.$r["vin"]));
						$nn_f = $r["user_name"].'-'.$r["mark"].'-'.$r["vin"];
						$name_file_pdf = $api->Strings->translit($nn_f).'.pdf';
						$sql_ .= ", `pdf_file`='".$name_file_pdf."', `excel_file`=''";
					}
															
					if ($_POST['file'] == 'EXCEL')
					{
						$name_file_excel = 'order_'.$id.'_'.date("YmdHis").'.xlsx';
						$sql_ .= ", `excel_file`='".$name_file_excel."', `pdf_file`=''";
					}
					
					$sql_update = "UPDATE `i_order` SET ".$sql_." WHERE `id`='".$id."'";
					$update = mysql_query($sql_update);	
					
					$com_name_new = stripslashes($r["com_name"]);
					$com_ = explode('|', $com_name_new);
					if (@$com_[0] != '')
						$com_name_new = trim($com_[0]);
					
					if ($_POST['file'] == 'PDF' && $name_file_pdf!='')			
					{
						require($_SERVER["DOCUMENT_ROOT"]."/order/order_pdf.php");

						include_once($_SERVER["DOCUMENT_ROOT"]."/libs/mpdf/mpdf.php");
												
						//$mpdf = new mPDF();
						//$mpdf->WriteHTML($strPdf);
						
						$mpdf = new mPDF('utf-8', 'A4', '', '', 8, 8, 7, 7, 8, 8);
						$mpdf->list_indent_first_level = 0;
						$mpdf->WriteHTML($strPdf, 2);
						
						$mpdf->Output($_SERVER['DOCUMENT_ROOT'].'/upload/order/'.$name_file_pdf,'F');
						
						echo '
						<script type="text/javascript">
							setTimeout(function() { self.location = "/order/load_pdf.php?load='.$id.'"; }, 50);
						</script>
						';
					}
					
					if ($_POST['file'] == 'EXCEL' && $name_file_excel!='')			
					{
						require($_SERVER["DOCUMENT_ROOT"]."/order/order_excel.php");													
						
						echo '
						<script type="text/javascript">
							setTimeout(function() { self.location = "/order/load_excel.php?load='.$id.'"; }, 50);
						</script>
						';
					}
				}
				else
					echo '
					<script type="text/javascript">			
						alert("Чтобы создать PDF должен быть статус «Готов»!");						
					</script>
					';	
			}
		}
		
		// СКАЧАТЬ ФАЙЛ PDF ФАЙЛЫ в more.php ==================
		else if (
			($_POST['do'] == 'getPDF') &&
			(isset($_POST['file']) && ($_POST['file'] == '1' || $_POST['file'] == '2' || $_POST['file'] == '3' || $_POST['file'] == '4')) &&
			(isset($_POST['pdf_num']) && $_POST['pdf_num'] != '') &&
			(isset($_POST['pdf_seriya']) && $_POST['pdf_seriya'] != '') &&
			(isset($_POST['date_oform']) && $_POST['date_oform'] != '') &&
			(isset($_POST['id_temp']) && intval($_POST['id_temp']) != 0) &&
			(isset($_POST['id']) && intval($_POST['id']) != 0)
		)
		{	
			if ($api->Managers->man_block == 1 || $api->Managers->man_block == 2 || $api->Managers->man_block == 5)
			{
				$pdf_num = trim($api->Strings->pr($_POST["pdf_num"]));	
				$pdf_seriya = trim($api->Strings->pr($_POST["pdf_seriya"]));		
				$date_oform = trim($api->Strings->pr($_POST["date_oform"]));		
				$id_temp = intval($_POST['id_temp']);			
				$id = intval($_POST['id']);			

				$s=mysql_query("SELECT * FROM `i_order` WHERE `id`='".$id."' AND `status`=3 LIMIT 1");
				if (mysql_num_rows($s) > 0)
				{ 
					$r=mysql_fetch_array($s);	
															
					$sql_ = "`pdf_num`='".$pdf_num."', `pdf_seriya`='".$pdf_seriya."', `id_temp`='".$id_temp."', `date_oform`='".$date_oform."'";					
					
					if ($_POST['file'] == '1')			$type_file = 'protocol';
					else if ($_POST['file'] == '2')		$type_file = 'reshenie';
					else if ($_POST['file'] == '3')		$type_file = 'zayavka';
					else if ($_POST['file'] == '4')		$type_file = 'dogovor';
					
					$nn_f = $r["user_name"].'-'.$r["mark"].'-'.$r["vin"].'-'.$type_file.'-'.$r["id"];
					$name_file_pdf = $api->Strings->translit($nn_f).'.pdf';
					
					$dirFile = $_SERVER['DOCUMENT_ROOT'].'/upload/order/'.$type_file.'/'.$name_file_pdf;
					if (is_file($dirFile)) { unlink($dirFile); }																									
																									
					$sql_update = "UPDATE `i_order` SET ".$sql_." WHERE `id`='".$id."'";
					//$update = mysql_query($sql_update);	
					
					$com_name_new = stripslashes($r["com_name"]);
					$com_ = explode('|', $com_name_new);
					if (@$com_[0] != '')
						$com_name_new = trim($com_[0]);
										
					require($_SERVER["DOCUMENT_ROOT"]."/order/pdf_files/".$type_file.".php");

					include_once($_SERVER["DOCUMENT_ROOT"]."/libs/mpdf/mpdf.php");																		

					$mpdf = new mPDF('utf-8', 'A4', '', '', 8, 8, 7, 7, 8, 8);
					$mpdf->list_indent_first_level = 0;
					$mpdf->WriteHTML($strPdf, 2);

					$mpdf->Output($_SERVER['DOCUMENT_ROOT'].'/upload/order/'.$type_file.'/'.$name_file_pdf,'F');

					echo '
					<script type="text/javascript">
						setTimeout(function() { self.location = "/order/load_pdf.php?file='.$id.'&type='.intval($_POST['file']).'"; }, 50);
					</script>
					';															
				}
				else
					echo '
					<script type="text/javascript">			
						alert("Чтобы создать PDF должен быть статус «Готов»!");						
					</script>
					';	
			}
		}
		
		// УДАЛИТЬ ФОТО КОМПАНИИ =================
		else if (
			($_POST['do'] == 'deleteFotoBroker') &&
			isset($_POST['type']) && (intval($_POST['type']) >= 1 && intval($_POST['type']) <= 10)
		)
		{					
			$type = intval($_POST['type']);			
			$foto_name = $api->Strings->pr($_POST['foto']);
							
			if ($foto_name != '')
			{
				// удалить файл ============
				if ($foto_name!='')
				{
					$dirFile = $_SERVER['DOCUMENT_ROOT'].'/upload/foto_broker/'.$foto_name;
					if (is_file($dirFile)) { unlink($dirFile); }
				}										

				echo '
				<script type="text/javascript">			
					jQuery("#add_foto'.$type.'").show();
					jQuery("#delete_foto'.$type.'").hide();
					jQuery("#load_to_foto'.$type.'").html("");										
				</script>
				';	
			}			
		}
		
		// EXCEL с заявками по досмотрщикам
		else if (
			($_POST['do'] == 'get_ExcelOder')
		)
		{	
			if ($api->Managers->man_block == 1 || $api->Managers->man_block == 2 || $api->Managers->man_block == 5)
			{
				$sql_get = "";
		
				$dateSS = '';
				$dateDD = '';

				if (isset($_POST["dateSS"]) && $api->Strings->pr($_POST["dateSS"]) != '')
					$dateSS = $api->Strings->pr($_POST["dateSS"]);
				if (isset($_POST["dateDD"]) && $api->Strings->pr($_POST["dateDD"]) != '')
					$dateDD = $api->Strings->pr($_POST["dateDD"]);

				if ($dateSS != '' && $dateDD == '')
					$sql_get = " AND (`create_date` > '".$dateSS." 00:00:00')";
				else if ($dateSS == '' && $dateDD != '')
					$sql_get = " AND (`create_date` < '".$dateDD." 23:59:59')";
				else if ($dateSS != '' && $dateDD != '')
					$sql_get = " AND (`create_date` > '".$dateSS." 00:00:00' AND `create_date` < '".$dateDD." 23:59:59')";
				
				if (isset($_POST["status"]) && (intval($_POST["status"]) >= 1 && intval($_POST["status"]) <= 4))
					$sql_get .= " AND `status`=".intval($_POST["status"]);
				else if (
					(isset($_POST["status"]) && intval($_POST["status"]) == 10) &&
					(
						$api->Managers->man_block == 1 || // админ 
						$api->Managers->man_block == 5 // глав менеджер
					)
				)
					$sql_get .= " AND `status`=0";
				
				$sql_wh = "";															
				if (isset($_POST["exam"]) && intval($_POST["exam"])==1)
					$sql_wh = " AND (`id_exam` > 0 AND `id_exam` IS NOT NULL)";
				else if (isset($_POST["exam"]) && intval($_POST["exam"])==0)
					$sql_wh = " AND (`id_exam`=0 OR `id_exam` IS NULL)";
				else if (isset($_POST["exam"]) && intval($_POST["exam"]) > 1)
					$sql_wh = " AND `id_exam`='".intval($_POST["exam"])."'";
				
				$order_by = "`create_date` DESC, `id` DESC";					

				$i=1;
				$per_page = 100;
				$sql_ = "FROM `i_order` WHERE `user_name` IS NOT NULL".$sql_get.$sql_wh;
				$sql_query = "SELECT * ".$sql_." ORDER BY ".$order_by;

				$s=mysql_query($sql_query);
				if (mysql_num_rows($s) > 0)
				{ 
					$name_file_excel = 'orders_'.date("YmdHis").'.xlsx';
					$dirFile = $_SERVER['DOCUMENT_ROOT'].'/upload/excel_exam/'.$name_file_excel;
					if (is_file($dirFile)) { unlink($dirFile); }
					
					include_once($_SERVER["DOCUMENT_ROOT"].'/libs/phpexcel/Classes/PHPExcel.php');
					include_once($_SERVER["DOCUMENT_ROOT"].'/libs/phpexcel/Classes/PHPExcel/IOFactory.php');
					include_once($_SERVER["DOCUMENT_ROOT"].'/libs/phpexcel/Classes/PHPExcel/Writer/Excel2007.php');

					$objPHPExcel = new PHPExcel();
					$page = $objPHPExcel->setActiveSheetIndex(0);
					
					$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
					$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
					$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
					$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(40);
					$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
					$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);		
					$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(30);
					
					$objPHPExcel->getActiveSheet()->setCellValue('A1', 'VIN');
					$objPHPExcel->getActiveSheet()->setCellValue('B1', 'МАРКА');
					$objPHPExcel->getActiveSheet()->setCellValue('C1', 'КОММЕРЧЕСКОЕ НАИМЕНОВАНИЕ');
					$objPHPExcel->getActiveSheet()->setCellValue('D1', 'ФИО');
					$objPHPExcel->getActiveSheet()->setCellValue('E1', 'ГОД ВЫПУСКА');
					$objPHPExcel->getActiveSheet()->setCellValue('F1', 'УВЭОС (СОС кнопка)');					
					$objPHPExcel->getActiveSheet()->setCellValue('G1', 'Статус');
					
					$j=2;
					while($r=mysql_fetch_array($s))						
					{
						$status = 'не просмотрен';						
						$sF = mysql_query("SELECT * FROM `i_foto` WHERE `id_order`='".$r["id"]."' LIMIT 1");
						if (mysql_num_rows($sF) > 0)
						{
							$rF=mysql_fetch_array($sF);

							$count = 0;		
							for($i=1; $i<=12; $i++)
							{								
								$dirFoto = $_SERVER['DOCUMENT_ROOT'].'/upload/foto/'.$rF["foto_".$i];
								if (is_file($dirFoto)) { $count++; }																
							}
							
							$status = 'Загружено '.$count.' фото';
							
							if ($rF["link_video"] != '')
								$status .= ', ссылка на видео сохранена';
							else
								$status .= ', ссылка на видео не сохранена';
								
						}
						
						$objPHPExcel->getActiveSheet()->setCellValue('A'.$j, $r["vin"]);
						$objPHPExcel->getActiveSheet()->setCellValue('B'.$j, stripslashes($r["mark"]));
						$objPHPExcel->getActiveSheet()->setCellValue('C'.$j, stripslashes($r["com_name"]));
						$objPHPExcel->getActiveSheet()->setCellValue('D'.$j, stripslashes($r["user_name"]));
						$objPHPExcel->getActiveSheet()->setCellValue('E'.$j, intval($r["year"]));
						$objPHPExcel->getActiveSheet()->setCellValue('F'.$j, (intval($r["uvesos"])==1 ? 'СОС ЕСТЬ' : 'СОС НЕТ'));
						$objPHPExcel->getActiveSheet()->setCellValue('G'.$j, $status);

						$j++;
					}
										
					$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
					$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

					$page->setTitle('Заявки по досмотрщикам');						

					$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
					$objWriter->save($_SERVER["DOCUMENT_ROOT"]."/upload/excel_exam/".$name_file_excel);
																																
					echo '
					<script type="text/javascript">
						setTimeout(function() { self.location = "/order/load_excel.php?exam=1"; }, 50);
					</script>
					';
					
				}				
			}
		}
		
		// ОТПРАВИТЬ МАКЕТ НА Whatsapp 
		else if (
			($_POST['do'] == 'sendWh') &&
			(isset($_POST['user_phone']) && $_POST['user_phone'] != '') &&			
			(isset($_POST['id']) && intval($_POST['id']) != 0)
		)
		{	
			if ($api->Managers->man_block == 1 || $api->Managers->man_block == 2 || $api->Managers->man_block == 5)
			{
				$user_phone = trim($api->Strings->pr($_POST["user_phone"]));								
				$id_order = intval($_POST['id']);			

				$s=mysql_query("SELECT * FROM `i_order` WHERE `id`='".$id_order."' LIMIT 1");
				if (mysql_num_rows($s) > 0)
				{ 
					$r=mysql_fetch_array($s);	
						
					function randomPassword() 
					{						
						$alphabet = 'abcdefghijklmnopqrstuvwxyz1234567890';
						$pass = array();
						$alphaLength = strlen($alphabet) - 1;
						for ($i = 0; $i < 8; $i++) {
							$n = rand(0, $alphaLength);
							$pass[] = $alphabet[$n];
						}
						return implode($pass);
					}
					$hash = randomPassword();
					
					$pdf_file = $r["pdf_file"];
					$excel_file = $r["excel_file"];
					$pdf_file_client = $r["pdf_file_client"];

					// удалить старыt файлы ============
					if ($pdf_file!='')
					{
						$dirFile = $_SERVER['DOCUMENT_ROOT'].'/upload/order/'.$pdf_file;
						if (is_file($dirFile)) { unlink($dirFile); }
					}	

					if ($excel_file!='')
					{
						$dirFile = $_SERVER['DOCUMENT_ROOT'].'/upload/order/'.$excel_file;
						if (is_file($dirFile)) { unlink($dirFile); }
					}
					
					if ($pdf_file_client!='')
					{
						$dirFile = $_SERVER['DOCUMENT_ROOT'].'/upload/client_pdf/'.$pdf_file_client;
						if (is_file($dirFile)) { unlink($dirFile); }
					}
					
					$nn_f = trim($api->Strings->pr($r["user_name"])).'-'.trim($api->Strings->pr($r["mark"])).'-'.trim($api->Strings->pr($r["vin"]));
					$pdf_file_client_new = $api->Strings->translit($nn_f).'.pdf';
					
					$sql_update = "UPDATE `i_order` SET `user_phone`='".$user_phone."', `hash`='".$hash."', `pdf_file_client`='".$pdf_file_client_new."' WHERE `id`='".$id_order."'";
					$update = mysql_query($sql_update);	
						
					$com_name_new = stripslashes($r["com_name"]);
					$com_ = explode('|', $com_name_new);
					if (@$com_[0] != '')
						$com_name_new = trim($com_[0]);
					
					require($_SERVER["DOCUMENT_ROOT"]."/order/order_pdf_client.php");
					include_once($_SERVER["DOCUMENT_ROOT"]."/libs/mpdf/mpdf.php");

					$mpdf = new mPDF('utf-8', 'A4', '', '', 8, 8, 7, 7, 8, 8);
					$mpdf->list_indent_first_level = 0;
					$mpdf->WriteHTML($strPdf, 2);

					$mpdf->Output($_SERVER['DOCUMENT_ROOT'].'/upload/client_pdf/'.$pdf_file_client_new,'F');
					
					include_once($_SERVER["DOCUMENT_ROOT"].'/wazzup/wazzup.php');										
					
					$phone = str_replace('(', '', str_replace(')', '', $user_phone));															
					$message = 'Здравствуйте '.$r["user_name"].' Ваш макет сертификата (СБКТС) на «'.$r["mark"].' '.$com_name_new.'» для проверкипройдите по ссылке, убедительная просьба подтвердить в кратчайшие сроки:
'.($r["date_issue"]!= '' ? '(Дата выпуска: '.$api->Strings->date('ru',$r["date_issue"],'sql','date').')' : '' ).'
https://eurotest.kz/info/'.$hash;
					$contentUri = '';

					// отправить сообщение 
					$messageId = $wazzup->Watsapp->sendMessage($phone, $message, $contentUri);
													
					echo '
					<span style="color:#53b374;">Вы успешно отправили макет!</span>					
					';
				}
			}
		}
		
	}

	exit;
}
?>
