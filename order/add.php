<?
$lang="ru";
$title="Создать задачу";
if (isset($_GET["edit"]) && intval($_GET["edit"])!=0)
	$title="Редактировать заявку";
$keywords="";
$description="";
require($_SERVER["DOCUMENT_ROOT"]."/libs/header.php");
if ($api->Managers->check_auth() == true)
{
	if (
		($api->Managers->man_block == 4 && !isset($_GET["edit"])) ||
		(
			($api->Managers->man_block == 1 || $api->Managers->man_block == 2 || $api->Managers->man_block == 5) //&&
			//(isset($_GET["edit"]) && intval($_GET["edit"])!=0)
		)
	)
	{
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
			$can_edit = 0; 
			if (
				(
					$api->Managers->man_block == 1 || // админ 
					$api->Managers->man_block == 2 || $api->Managers->man_block == 5 // менеджеры
				) &&
				(isset($_GET["edit"]) && intval($_GET["edit"])!=0)
			)
			{		
				$sql_wh = "";
				if ($api->Managers->man_block == 2)
					$sql_wh = " AND (`status`=2 OR `status`=4)";
				
				$id_company = 0;
				$s=mysql_query("SELECT * FROM `i_order` WHERE `id`='".intval($_GET["edit"])."'".$sql_wh." LIMIT 1");
				if (mysql_num_rows($s) > 0)
				{
					$r=mysql_fetch_array($s);
					
					$can_edit = 1;
					$uvesos = intval($r["uvesos"]);										
					$id_company = intval($r["id_broker"]);		
					
					foreach($columns as $k=>$v)				
						$fields[$v] = stripslashes($api->Strings->pr_plus($r[$v]));
					
					$have_op = 0;
					$sM=mysql_query("SELECT `level` FROM `i_baza` WHERE `task_type`='".$r["task_type"]."' GROUP BY `level` ORDER BY `level` ASC");
					if (mysql_num_rows($sM) > 0)
					{								
						$mark_option .= '<option value=""> выберите Тип </option>';
						while($rM=mysql_fetch_array($sM))
						{
							$selected = '';
							if (stripcslashes($rM["level"]) == stripcslashes($r["level"]))
							{
								$selected = ' selected';
								$have_op++;
							}
							
							$mark_option .= '<option value="'.stripcslashes($rM["level"]).'"'.$selected.'> '.stripcslashes($rM["level"]).' </option>';
						}													
					}
					
					if ($have_op == 0)
						$mark_option .= '<option value="'.stripcslashes($r["level"]).'" selected> '.stripcslashes($r["level"]).' </option>';		
					
					
					$have_op = 0;
					$sC=mysql_query("SELECT `com_name` FROM `i_baza` WHERE `task_type`='".$r["task_type"]."' AND `level`='".$r["level"]."' GROUP BY `com_name` ORDER BY `com_name` ASC");
					if (mysql_num_rows($sC) > 0)
					{		
						
						$com_name_option .= '<option value=""> выберите Тип </option>';
						while($rC=mysql_fetch_array($sC))
						{
							$selected = '';
							if (stripcslashes($rM["com_name"]) == stripcslashes($r["com_name"]))
							{
								$selected = ' selected';
								$have_op++;
							}
							
							$com_name_option .= '<option value="'.stripcslashes($rC["com_name"]).'"'.$selected.'> '.stripcslashes($rC["com_name"]).' </option>';
						}														
					}
					
					if ($have_op == 0)
						$com_name_option .= '<option value="'.stripcslashes($r["com_name"]).'" selected> '.stripcslashes($r["com_name"]).' </option>';	
					
					$have_op = 0;
					$sY=mysql_query("SELECT `year` FROM `i_baza` WHERE `task_type`='".$r["task_type"]."' AND `level`='".$r["level"]."' AND `com_name`='".$r["com_name"]."' GROUP BY `year` ORDER BY `year` ASC");
					if (mysql_num_rows($sY) > 0)
					{								
						$year_option .= '<option value=""> выберите Год выпуска </option>';
						while($rY=mysql_fetch_array($sY))
						{
							$selected = '';
							if (stripcslashes($rM["year"]) == stripcslashes($r["year"]))
							{
								$selected = ' selected';
								$have_op++;
							}
							
							$year_option .= '<option value="'.stripcslashes($rY["year"]).'"'.$selected.'> '.stripcslashes($rY["year"]).' </option>';
						}													
					}
					
					if ($have_op == 0)
						$year_option .= '<option value="'.stripcslashes($r["year"]).'" selected> '.stripcslashes($r["year"]).' </option>';		
					
					$have_op = 0;
					$sV=mysql_query("SELECT `volume` FROM `i_baza` WHERE `task_type`='".$r["task_type"]."' AND `level`='".$r["level"]."' AND `com_name`='".$r["com_name"]."' AND `year`='".$r["year"]."' GROUP BY `volume` ORDER BY `volume` ASC");
					if (mysql_num_rows($sV) > 0)
					{								
						$volume_option = '<option value=""> выберите Тип </option>';
						while($rV=mysql_fetch_array($sV))
						{
							$selected = '';
							if (stripcslashes($rM["volume"]) == stripcslashes($r["volume"]))
							{
								$selected = ' selected';
								$have_op++;
							}
							
							$volume_option .= '<option value="'.stripcslashes($rV["volume"]).'"'.$selected.'> '.stripcslashes($rV["volume"]).' </option>';
						}														
					}
					
					if ($have_op == 0)
						$volume_option .= '<option value="'.stripcslashes($r["volume"]).'" selected> '.stripcslashes($r["volume"]).' </option>';	
				}
				/*
				foreach($columns as $k=>$v)
				{
					$dis_field[$v] = '';										
				}
				*/
			}
		
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
			$name_ru["solving_avg"] = 'Средняя время для выполнения';
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
							<option value="" style = "color: white !important;"> выберите тип </option>
							<option value="stegano"<?=($fields[$v] == 'stegano' ? ' selected' : '')?>> stegano </option>
							<option value="web"<?=($fields[$v] == 'web' ? ' selected' : '')?>> web </option>
							<option value="crypto"<?=($fields[$v] == 'crypto' ? ' selected' : '')?>> crypto </option>
							<option value="прочее"<?=($fields[$v] == 'прочее' ? ' selected' : '')?>> прочее </option>
						</select>						
						<? } else if ($v == 'level') { ?>
						<div id="<?=$v?>_bl"<?=($fields["task_type"]=='прочее' ? ' style="display:none;"' : '')?>>
						<select class="form-control" id="<?=$v?>">
							<option value=""> выберите сложность </option>
							<option value="easy"<?=($fields[$v] == 'easy' ? ' selected' : '')?>> easy </option>
							<option value="medium"<?=($fields[$v] == 'medium' ? ' selected' : '')?>> medium </option>
							<option value="hard"<?=($fields[$v] == 'hard' ? ' selected' : '')?>> hard </option>
						</select>	
						</div>
						<? } else { ?>
							<? if ($type_field[$v] == 'input') { ?>
                        <input type="text" class="form-control<?=($v=='user_phone' ? ' phone' : '').($v=='date_issue' ? ' dateInput' : '').($v=='user_iin' ? ' only_int' : '').($v=='solving_avg' ? ' solving_avg' : '')?>"<?=($v=='solving_avg' ? ' maxlength="17"' : '').($v=='user_iin' ? ' maxlength="12"' : '')?> id="<?=$v?>" value="<?=$fields[$v]?>"<?=$dis_field[$v]?> />
							<? } else { ?>
						<textarea class="form-control" id="<?=$v?>"<?=$dis_field[$v]?>><?=str_replace('\n', '&#13;', $fields[$v])?></textarea>
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
                <button class="btn btn-success action" onclick="addZ();"><?=($can_edit == 1 ? 'Сохранить' : 'Добавить')?></button>
                <div class="loading">
                    <img src="/library/img/load.gif" /> Ваш запрос обрабатывается...
                </div>
                <div class="protocol_add"></div>
            </div>

		</div>
		<script type="text/javascript">
						
			function choosePhone()
			{
				var phone_new = jQuery("#dir_phone").val();
				
				jQuery("#user_phone").val(phone_new);
			}
			
			function chooseCar(field)
			{
				var err_key = 0;
				var focused = 0;
				
				jQuery(".card-body select").css("border-color", "#c9cbcd");
				
				<?php /*?>if (jQuery("#"+field).val() == '')				
					err_key = 1;<?php */?>									
					
					if (err_key == 0)
					{
						jQuery.ajax(
						{
							url: "ajax.php",
							data: "do=chooseCar&field="+field+"&task_type="+jQuery("#task_type").val()+"&level="+jQuery("#level").val()+"&com_name="+jQuery("#com_name").val()+"&volume="+jQuery("#volume").val()+"&year="+jQuery("#year").val()+"&x=secure",
							type: "POST",
							dataType : "html",
							cache: false,

							beforeSend: function()		{ jQuery(".protocol_add").html("");  jQuery("#load_"+field).show(); },
							success:  function(data)	{ jQuery(".protocol_add").html(data); jQuery("#load_"+field).hide(); },
							error: function()			{ alert("Невозможно связаться с сервером"); jQuery("#load_"+field).hide(); }
						});
					}
			}
			
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
					<? } else { ?>
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
		

		</script>
		<?
	}
	else
		require($_SERVER["DOCUMENT_ROOT"]."/text_noAcces.php");
}
else
	require($_SERVER["DOCUMENT_ROOT"]."/text_noAuth.php");
require($_SERVER["DOCUMENT_ROOT"]."/libs/footer.php");?>
