<?
$lang="ru";
$title="Создать задачу";
if (isset($_GET["edit"]) && intval($_GET["edit"])!=0)
	$title="Редактировать задачу";
$keywords="";
$description="";
require($_SERVER["DOCUMENT_ROOT"]."/libs/header.php");

if ($api->Managers->check_auth() == true)
{
	if (
		($api->Managers->man_block == 4 && isset($_GET["edit"])) ||
		(
			($api->Managers->man_block == 1 || $api->Managers->man_block == 2 || $api->Managers->man_block == 5) //&&
			//(isset($_GET["edit"]) && intval($_GET["edit"])!=0)
		)
	)
	{
		require_once '../get_encr_key.php';
		

		?>
		<div class="card">
			<?
			$no_need = Array('id', 'create_date', 'id_man', 'id_exam', 'id_broker', 'link_man', 'link_man2', 'pdf_num', 'pdf_seriya', 'pdf_file', 'excel_file', 'date_edit_exam', 'status', 'status2', 'id_temp', 'name_temp', 'info_temp', 'd_temp', 'date_oform', 'pdf_file_client', 'hash');
			$table = 'i_order';
			$query = "SHOW COLUMNS FROM $table";
			if ($output = mysql_query($query)):
				$columns = array();
				while($result = mysql_fetch_assoc($output)):
					if (!in_array($result['Field'], $no_need))
						$columns[] = $result['Field'];
				endwhile;
			endif;

			$fields = Array(); $type_field = Array(); $dis_field = Array(); $mandat = Array(); $java = '';
			foreach($columns as $k=>$v)
			{
				$fields[$v] = '';
				$type_field[$v] = 'textarea';
				$dis_field[$v] = ' disabled';
				$mandat[$v] = 0;
			}				
			
			$uvesos = 0;
			$mark_option = ''; $year_option = ''; $com_name_option = ''; $type_option = '';
			
			
		
			if (
				(
					$api->Managers->man_block == 1 || // админ 
					$api->Managers->man_block == 2 || $api->Managers->man_block == 5 // менеджеры
				)
			)
			{
				foreach($columns as $k=>$v)
				{
					$dis_field[$v] = '';										
				}	
			}

			$name_ru = Array();				
			$name_ru["task_name"] = 'Название Задачи';
			$name_ru["task_type"] = 'Категория Задачи';
			$name_ru["solving_avg"] = 'Среднее время для выполнения';
			$name_ru["level"] = 'Сложность';
			$name_ru["link"] = 'Ссылка задачи';
			$name_ru["description"] = 'Описание';
			$name_ru["flag"] = 'Флаг';
			$name_ru["points"] = 'Балл';
			

			$mandat["task_name"] = 1;
			$mandat["task_type"] = 1;
			$mandat["solving_avg"] = 1;
			$mandat["level"] = 1;
			$mandat["link"] = 1;
			$mandat["description"] = 1;
			$mandat["flag"] = 1;
			$mandat["points"] = 1;


			$type_field["task_name"] = 'input';
			$type_field["solving_avg"] = 'input';
			$type_field["points"] = 'input';
			$type_field["flag"] = 'input';

			$dis_field["task_name"] = '';
			$dis_field["task_type"] = '';	
			$dis_field["solving_avg"] = '';
			$dis_field["link"] = '';
			$dis_field["description"] = '';
			$dis_field["points"] = '';
			$dis_field["flag"] = '';

			$can_edit = 0;

			if (isset($_GET["edit"]) && intval($_GET["edit"]) != 0)
			{
				$can_edit = 1;
				$sql_ = '';										
				$s=mysql_query("SELECT * FROM `i_order` WHERE `id`='".intval($_GET["edit"])."'".$sql_." ORDER BY `id` ASC LIMIT 1");
				if (mysql_num_rows($s) > 0)
				{
					
					$r=mysql_fetch_array($s);

					$dis_field["task_name"] = $r["task_name"];
					$dis_field["task_type"] = $r["task_type"];
					$dis_field["solving_avg"] = $r["solving_avg"];
					$dis_field["link"] = $r["link"];
					$dis_field["description"] = $r["description"];
					$dis_field["points"] = $r["points"];
					$dis_field["level"] = $r["level"];
					$dis_field["flag"] = $flag = decryptPassword($r["flag"], $encryption_key);
					
					// print_r($dis_field);
					// $hashed_password = password_hash($password_value, PASSWORD_DEFAULT);
					
				}
				// print_r($active_value); echo "<br>";
				// print_r($event_name_value); echo "<br>";
				// print_r($description_value); echo "<br>";
				// print_r($date_value); echo "<br>";
				// print_r($link_value); echo "<br>";
			}

			?>                
            <style>
				body {
					background-color: #0d1b2a;
					
				}

				.card {
					background-color: #1a2035; 
					background: #12192c;
					background: -webkit-linear-gradient(to right, #1b2735, #12192c);
					background: linear-gradient(to right, #1b2735, #12192c);
					box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
				}

				.card-body {
					background-color: #1a2035;
					color: white !important;
					background: #12192c;
					background: -webkit-linear-gradient(to right, #1b2735, #12192c);
					background: linear-gradient(to right, #1b2735, #12192c);
					box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
				}
				.card-body .row{
					
					color: white !important;
				}
				.card-title {
					color: white !important;
					font-weight: bold;
				}
				.form-group{
					
					color: white !important;
				}
				
				.btn {
					background-color: white;
				}
				
				.form-control {
					background-color: white; 
					color: black;
				}
				/* .container {
					background-color: black;
				} */
			</style>  				
			<? foreach($columns as $k=>$v)	{ ?>
				<? if ($v == 'task_name') { ?>
			<div class="card-header">
				<div class="card-title">Создание Задачи</div>
			</div>
			<div class="card-body">
				<? } else if ($v == 'task_type') { ?>
			<div class="card-body">
				<? } else if ($v == 'type') { ?>
			<div class="card-header" style="border-top: 1px solid #ebecec !important;">
				<div class="card-title">Общие характеристики транспортного средства</div>
			</div>		
			<div class="card-body">
				<? } ?>
				
				<div class="form-group form-show-validation row">
				<label for="<?=$v?>" class="col-lg-4 col-md-3 col-sm-4 mt-sm-2 text-right"  style = "color: white !important;">
						<? if ($v != 'uvesos') { ?>
						<?=$name_ru[$v]?> <?=($mandat[$v]==1?'<span class="required-label">*</span>':'')?>
						<? } ?>
					</label>
					
					<?
					// СПРАВОЧНИК ===
					if ($v=='user_phone')
					{
						$sPhones=mysql_query("SELECT * FROM `i_dir_phone` WHERE `phone` IS NOT NULL ORDER BY `name` ASC");
						if (mysql_num_rows($sPhones) > 0)
						{
							echo '
							<div class="col-lg-2 col-md-6 col-sm-5">
								<select class="form-control multiselect" id="dir_phone" onChange="choosePhone()">
									<option value=""> выберите телефон </option>
							';
							
							while($rPhones=mysql_fetch_array($sPhones))
							{
								echo '<option value="'.$rPhones["phone"].'"'.($fields["user_phone"] == $rPhones["phone"] ? ' selected' : '').'> '.stripslashes($rPhones["name"]).' </option>';
							}
							
							echo '
								</select>	
							</div>
							<div class="col-lg-3 col-md-3 col-sm-3">
							';
						}		 												
					}
					else 
					{
					?>					
                    <div class="col-lg-5 col-md-9 col-sm-8">
					<? } ?>
						
						<? if ($v == 'task_type') { ?>
						<select class="form-control" id="<?=$v?>">
							<option value="" style="color: white !important;"> выберите тип </option>
							<option value="stegano"<?=($dis_field[$v] == 'stegano' ? ' selected' : '')?>> stegano </option>
							<option value="web"<?=($dis_field[$v] == 'web' ? ' selected' : '')?>> web </option>
							<option value="crypto"<?=($dis_field[$v] == 'crypto' ? ' selected' : '')?>> crypto </option>
							<option value="прочее"<?=($dis_field[$v] == 'прочее' ? ' selected' : '')?>> прочее </option>
						</select>					
						<? } else if ($v == 'level') { ?>
						<div id="<?=$v?>_bl"<?=($fields["task_type"]=='прочее' ? ' style="display:none;"' : '')?>>
							<select class="form-control" id="<?=$v?>">
								<option value=""> выберите сложность </option>
								<option value="easy"<?=($dis_field[$v] == 'easy' ? ' selected' : '')?>> easy </option>
								<option value="medium"<?=($dis_field[$v] == 'medium' ? ' selected' : '')?>> medium </option>
								<option value="hard"<?=($dis_field[$v] == 'hard' ? ' selected' : '')?>> hard </option>
							</select>    
						</div>
						<? } else { ?>
							<? if ($type_field[$v] == 'input') { ?>
                        <input type="text" class="form-control<?=($v=='user_phone' ? ' phone' : '').($v=='date_issue' ? ' dateInput' : '').($v=='user_iin' ? ' only_int' : '').($v=='solving_avg' ? ' solving_avg' : '')?>"<?=($v=='solving_avg' ? ' maxlength="17"' : '').($v=='user_iin' ? ' maxlength="12"' : '')?> id="<?=$v?>" value="<?=$dis_field[$v]?>" />
							<? } else { ?>
						<textarea class="form-control" id="<?=$v?>"<?=$dis_field[$v]?>><?=str_replace('\n', '&#13;', $dis_field[$v])?></textarea>
							<? } ?>
						<? } ?>
                        <span class="control__help" id="error_<?=$v?>"></span>
                    </div>
					<? if ($v == 'task_type') { ?>
					<div class="col-lg-1 col-md-1 col-sm-1">
						<span class="loading" id="load_<?=$v?>"><img src="/library/img/load.gif" /></span>
					</div>
					<? } ?>
                </div>												
		
				
				<? if ( $v == 'solving_avg') { ?>
			</div>
				<? } ?>
				
				<? if ($v == 'solving_avg') { ?>

				<? } ?>
				
			<? } ?>								
				
			<div class="card-action t-right">
				<a class="btn btn-warning" style="float:left" href="javascript:history.go(-1)">Вернуться назад</a>
				<? if (isset($_GET["edit"]) && intval($_GET["edit"]) != 0) { ?>
                	<button type="button" class="btn btn-danger action"  style="margin-right:50px" onclick="deleteTask();">Удалить</button>
                <? } ?>
                <button class="btn btn-success action" onclick="addZ();"><?=($can_edit == 1 ? 'Сохранить' : 'Добавить')?></button>
                <div class="loading">
                    <img src="/library/img/load.gif" /> Ваш запрос обрабатывается...
                </div>
                <div class="protocol_add"></div>
				<div class="protocol_del"></div>
            </div>

		</div>
		<script type="text/javascript">
						
			
			function addZ() {
				var err_key = 0;
				var focused = 0;

				jQuery(".card-body input").css("border-color", "#c9cbcd");
				jQuery(".card-body textarea").css("border-color", "#c9cbcd");
				jQuery(".control__help").html('').hide();

				<? foreach($mandat as $k=>$v) { ?>
					<? if ($k=='level') { ?>
						if (jQuery("#<?=$k?>").val() == '' && jQuery("#task_type").val() != 'прочее') {
							err_key = 1;
							jQuery("#<?=$k?>").css("border-color", "#f00");
							jQuery("#error_<?=$k?>").html('Не заполнено поле <?=$name_ru[$k]?>').css("display", "inline-block");
							if (focused == 0) { jQuery("#<?=$k?>").focus(); focused = 1; }
						}
						
						if (jQuery("#<?=$k?>_input").val() == '' && jQuery("#task_type").val() == 'прочее') {
							err_key = 1;
							jQuery("#<?=$k?>_input").css("border-color", "#f00");
							jQuery("#error_<?=$k?>").html('Не заполнено поле <?=$name_ru[$k]?>').css("display", "inline-block");
							if (focused == 0) { jQuery("#<?=$k?>_input").focus(); focused = 1; }
						}
					<?} else if($k == "points"){?>
						if (jQuery("#points").val() == '')
						{
							err_key = 1;
							jQuery("#points").css("border-color", "#f00");
							jQuery("#error_points").html('Не заполнено поле points').css("display", "inline-block");
							if (focused == 0) { jQuery("#points").focus(); focused = 1; }
						} else {
							var password = jQuery("#points").val();
							var regex = /^\d+$/;

							if (!regex.test(password)) {
								err_key = 1;
								jQuery("#points").css("border-color", "#f00");
								jQuery("#error_points").html('Балл должен состоять только из цифр').css("display", "inline-block");
								if (focused == 0) { jQuery("#points").focus(); focused = 1; }
							}
						}
				
					<?} else { ?>
						<? if ($v == 1 && $k != 'solving_avg') { ?>
							if (jQuery("#<?=$k?>").val() == '') {
								err_key = 1;
								jQuery("#<?=$k?>").css("border-color", "#f00");
								jQuery("#error_<?=$k?>").html('Не заполнено поле <?=$name_ru[$k]?>').css("display", "inline-block");
								if (focused == 0) { jQuery("#<?=$k?>").focus(); focused = 1; }
							}
						<? } ?>
					<? } ?>
				<? } ?>

				if (jQuery("#solving_avg").val()=="") {
					err_key = 1;                    
					jQuery("#solving_avg").css("border-color", "#f00");
					jQuery("#error_vin").html("Не заполнено поле <?=$name_ru["solving_avg"]?>").css("display", "inline-block");;
					if (focused == 0) { jQuery("#solving_avg").focus(); focused = 1; }
				}

				if (err_key == 0) {
					var task_name = jQuery("#task_name").val();
					var task_type = jQuery("#task_type").val();
					var level = jQuery("#level").val();
					var link = jQuery("#link").val();
					var description = jQuery("#description").val();
					var flag = jQuery("#flag").val();
					var points = jQuery("#points").val();
					var solving_avg = jQuery("#solving_avg").val();

					jQuery.ajax({
						url: "ajax.php",
						data: "do=<?=($can_edit == 1 ? 'edit&edit='.intval($_GET["edit"]) : 'add')?>&task_name="+task_name+"&task_type="+task_type+"&level="+level+"&link="+link+"&description="+description+"&flag="+flag+"&points="+points+"&solving_avg="+solving_avg+"&x=secure"<?=$java?>,
						type: "POST",
						dataType: "html",
						cache: false,

						beforeSend: function() { 
							jQuery(".protocol_add").html(""); 
							jQuery(".action").hide(); 
							jQuery(".loading").show(); 
						},
						success: function(data) { 
							jQuery(".protocol_add").html(data); 
							jQuery(".loading").hide(); 
						},
						error: function() { 
							alert("Невозможно связаться с сервером"); 
							jQuery(".action").show(); 
							jQuery(".loading").hide(); 
						}
					});
				}
			} 
		
			<? if (isset($_GET["edit"]) && intval($_GET["edit"]) != 0) { ?>
				function deleteTask() {
					jQuery.ajax(
						{
							url: "ajax.php",
							data: "do=delete&edit=<?=intval($_GET["edit"])?>&x=secure",
							type: "POST",
							dataType : "html",
							cache: false,

							beforeSend: function() { 
								jQuery(".protocol_del").html(""); 
								jQuery(".action").hide(); 
								jQuery(".loading").show(); 
							},
							success: function(data) { 
								jQuery(".protocol_del").html(data); 
								jQuery(".loading").hide(); 
							},
							error: function() { 
								alert("Невозможно связаться с сервером"); 
								jQuery(".action").show(); 
								jQuery(".loading").hide(); 
							}
					});
				} 
			<? } ?>

		</script>
		<?
	}
	else
		require($_SERVER["DOCUMENT_ROOT"]."/text_noAcces.php");
}
else
	require($_SERVER["DOCUMENT_ROOT"]."/text_noAuth.php");
require($_SERVER["DOCUMENT_ROOT"]."/libs/footer.php");?>
