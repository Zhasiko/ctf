<?
$lang="ru";
$title="Добавить заявку";
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
				
				if ($v!='uvesos')
					$java .= '+"&'.$v.'="+jQuery("#'.$v.'").val()';
				
				// доп поля при выборе ПРОЧЕЕ
				if ($v=='mark' || $v=='com_name' || $v=='year' || $v=='volume')
					$java .= '+"&'.$v.'_input="+jQuery("#'.$v.'_input").val()';
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
					$sM=mysql_query("SELECT `mark` FROM `i_baza` WHERE `car_type`='".$r["car_type"]."' GROUP BY `mark` ORDER BY `mark` ASC");
					if (mysql_num_rows($sM) > 0)
					{								
						$mark_option .= '<option value=""> выберите Марку </option>';
						while($rM=mysql_fetch_array($sM))
						{
							$selected = '';
							if (stripcslashes($rM["mark"]) == stripcslashes($r["mark"]))
							{
								$selected = ' selected';
								$have_op++;
							}
							
							$mark_option .= '<option value="'.stripcslashes($rM["mark"]).'"'.$selected.'> '.stripcslashes($rM["mark"]).' </option>';
						}													
					}
					
					if ($have_op == 0)
						$mark_option .= '<option value="'.stripcslashes($r["mark"]).'" selected> '.stripcslashes($r["mark"]).' </option>';		
					
					
					$have_op = 0;
					$sC=mysql_query("SELECT `com_name` FROM `i_baza` WHERE `car_type`='".$r["car_type"]."' AND `mark`='".$r["mark"]."' GROUP BY `com_name` ORDER BY `com_name` ASC");
					if (mysql_num_rows($sC) > 0)
					{		
						
						$com_name_option .= '<option value=""> выберите Коммерческое наименование </option>';
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
					$sY=mysql_query("SELECT `year` FROM `i_baza` WHERE `car_type`='".$r["car_type"]."' AND `mark`='".$r["mark"]."' AND `com_name`='".$r["com_name"]."' GROUP BY `year` ORDER BY `year` ASC");
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
					$sV=mysql_query("SELECT `volume` FROM `i_baza` WHERE `car_type`='".$r["car_type"]."' AND `mark`='".$r["mark"]."' AND `com_name`='".$r["com_name"]."' AND `year`='".$r["year"]."' GROUP BY `volume` ORDER BY `volume` ASC");
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
			$name_ru["user_name"] = 'ФИО';
			$name_ru["user_iin"] = 'ИИН';
			$name_ru["user_uyr_adres"] = 'Юридический  адрес';
			$name_ru["user_fac_adres"] = 'Фактический адрес';
			$name_ru["user_phone"] = 'Телефон';
			$name_ru["user_fax"] = 'Факс';
			$name_ru["user_mail"] = 'E-mail';
			$name_ru["broker_name"] = 'Брокер';
			$name_ru["date_issue"] = 'Дата выписки';
			$name_ru["svh"] = 'СВХ';
			$name_ru["uvesos"] = 'УВЭОС (СОС кнопка)';
			$name_ru["car_type"] = 'ТИП АВТОМОБИИЛЯ';
			$name_ru["mark"] = 'МАРКА';
			$name_ru["com_name"] = 'КОММЕРЧЕСКОЕ НАИМЕНОВАНИЕ';
			$name_ru["year"] = 'ГОД ВЫПУСКА';
			$name_ru["volume"] = 'ОБЪЁМ';
			$name_ru["vin"] = 'VIN';
			$name_ru["type"] = 'Тип';
			$name_ru["chassis"] = 'Шасси';
			$name_ru["category"] = 'Категория';
			$name_ru["ek_class"] = 'Экологический класс';
			$name_ru["manufacturer"] = 'Изготовитель';
			$name_ru["manufacturer_uyr_adres"] = 'Изготовитель - юридический  адрес';
			$name_ru["manufacturer_fac_adres"] = 'Изготовитель - фактический адрес';
			$name_ru["zavod"] = 'Сборочный завод';
			$name_ru["zavod_adres"] = 'Сборочный завод - адрес';
			$name_ru["wheels"] = 'Колесная формула/ведущие колеса';
			$name_ru["privod"] = 'Схема компоновки транспортного средства';
			$name_ru["engine_location"] = 'Расположение двигателя';
			$name_ru["bode_type"] = 'Тип кузова/количество дверей (для категории М|)';
			$name_ru["count_place"] = 'Количество мест спереди/ cзади (для категории М1)';
			$name_ru["boot_space"] = 'Исполнение загрузочного пространства (для категории N)';
			$name_ru["cabina"] = 'Кабина (для категории N)';
			$name_ru["count_pass"] = 'Пассажировместимость (для категорий М2, Мз)';
			$name_ru["bagajnik"] = 'Общий объем багажных отделений (для категории Мз класса III)';
			$name_ru["count_place_m2"] = 'Количество мест для сидения (для категорий М2, M3,L)';
			$name_ru["rama"] = 'Рама (для категории L)';
			$name_ru["count_koles"] = 'Количество осей/колес (для категории О)';
			$name_ru["massa"] = 'Масса транспортного средства в снаряженном состоянии, кг';
			$name_ru["max_massa"] = 'Технически допустимая максимальная масса транспортного средства, кг';
			$name_ru["dlina"] = 'Габаритные размеры, мм -  длина';
			$name_ru["shirina"] = 'Габаритные размеры, мм -  ширина';
			$name_ru["vysota"] = 'Габаритные размеры, мм -  высота';
			$name_ru["baza_mm"] = 'База, мм';
			$name_ru["koleya"] = 'Колея передних/задних колес, мм';
			$name_ru["gibrid"] = 'Описание гибридного транспортного средства';
			$name_ru["dvigatel"] = 'Двигатель внутреннего сгорания (марка, тип)';
			$name_ru["count_cilindr"] = 'количество и расположение цилиндров';
			$name_ru["obem_cilindr"] = 'рабочий объем цилиндров, см3';
			$name_ru["stepen_sj"] = 'степень сжатия';
			$name_ru["max_mosh"] = 'максимальная мощность, кВт (мин.-1)';
			$name_ru["toplivo"] = 'Топливо';
			$name_ru["sys_pitanie"] = 'Система питания (тип)';
			$name_ru["sys_zajig"] = 'Система зажигания (тип)';
			$name_ru["sys_gaz"] = 'Система выпуска и нейтрализации отработавших газов';
			$name_ru["elektro"] = 'Электродвигатель электромобиля';
			$name_ru["rab_napr"] = 'Рабочее напряжение, В';
			$name_ru["max_30mosh"] = 'Максимальная 30-минутная мощность, кВт';
			$name_ru["nakop_energ"] = 'Устройство накопления энергии';
			$name_ru["transmisiya"] = 'Трансмиссия';
			$name_ru["elek_marka"] = 'Электромашина (марка, тип)';
			$name_ru["rab_napr2"] = 'Рабочее напряжение, В';
			$name_ru["max_30mosh2"] = 'Максимальная 30-минутная мощность, кВт';
			$name_ru["ceplenie"] = 'Сцепление (марка, тип)';
			$name_ru["korobka"] = 'Коробка передач (марка, тип)';
			$name_ru["podveska_pered"] = 'Подвеска(тип) - передняя';
			$name_ru["podveska_zad"] = 'Подвеска(тип) - задняя';
			$name_ru["rul_upravl"] = 'Рулевое управление (марка, тип)';
			$name_ru["tormoz"] = 'Тормозные системы (тип)';
			$name_ru["tormoz_rab"] = 'Тормозные системы (тип) -  рабочая';
			$name_ru["tormoz_zapas"] = 'Тормозные системы (тип) - запасная';
			$name_ru["tormoz_sto"] = 'Тормозные системы (тип) - стояночная';
			$name_ru["shiny"] = 'Шины';
			$name_ru["dop_obor"] = 'Дополнительное оборудование транспортного средства';

			$mandat["user_name"] = 1;
			$mandat["user_iin"] = 1;
			$mandat["user_phone"] = 1;
			$mandat["svh"] = 1;
			$mandat["vin"] = 1;
			$mandat["car_type"] = 1;
			$mandat["mark"] = 1;
			$mandat["com_name"] = 1;			
			$mandat["year"] = 1;
			$mandat["volume"] = 1;

			$type_field["user_name"] = 'input';
			$type_field["user_iin"] = 'input';
			$type_field["user_phone"] = 'input';
			$type_field["user_fax"] = 'input';
			$type_field["user_mail"] = 'input';
			$type_field["broker_name"] = 'input';
			$type_field["date_issue"] = 'input';
			$type_field["type"] = 'input';
			$type_field["chassis"] = 'input';
			$type_field["vin"] = 'input';		
			$type_field["category"] = 'input';
			$type_field["ek_class"] = 'input';	

			$dis_field["user_name"] = '';
			$dis_field["user_iin"] = '';
			$dis_field["user_uyr_adres"] = '';
			$dis_field["user_fac_adres"] = '';
			$dis_field["user_phone"] = '';
			$dis_field["user_fax"] = '';
			$dis_field["user_mail"] = '';
			$dis_field["broker_name"] = '';
			$dis_field["date_issue"] = '';
			$dis_field["svh"] = '';
			$dis_field["uvesos"] = '';
			$dis_field["car_type"] = '';												
			$dis_field["vin"] = '';
			?>                
                				
			<? foreach($columns as $k=>$v)	{ ?>
				<? if ($v == 'user_name') { ?>
			<div class="card-header">
				<div class="card-title">Информация о Заявителе</div>
			</div>
			<div class="card-body">
				<? } else if ($v == 'car_type') { ?>
			<div class="card-header" style="border-top: 1px solid #ebecec !important;">
				<div class="card-title">Транспортное средство</div>
			</div>		
			<div class="card-body">
				<? } else if ($v == 'type') { ?>
			<div class="card-header" style="border-top: 1px solid #ebecec !important;">
				<div class="card-title">Общие характеристики транспортного средства</div>
			</div>		
			<div class="card-body">
				<? } ?>
				
				<div class="form-group form-show-validation row">
                    <label for="<?=$v?>" class="col-lg-4 col-md-3 col-sm-4 mt-sm-2 text-right">
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
						
						<? if ($v == 'car_type') { ?>
						<select class="form-control" id="<?=$v?>" onChange="chooseCar('car_type')">
							<option value=""> выберите тип </option>
							<option value="легковой автомобиль"<?=($fields[$v] == 'легковой автомобиль' ? ' selected' : '')?>> легковой автомобиль </option>
							<option value="грузовой автомобиль"<?=($fields[$v] == 'грузовой автомобиль' ? ' selected' : '')?>> грузовой автомобиль </option>
							<option value="седельный тягач"<?=($fields[$v] == 'седельный тягач' ? ' selected' : '')?>> седельный тягач </option>
							<option value="прочее"<?=($fields[$v] == 'прочее' ? ' selected' : '')?>> прочее </option>
						</select>						
						<? } else if ($v == 'uvesos') { ?>
						<div class="form-check">
							<label class="form-check-label">
								<input class="form-check-input" type="checkbox" id="<?=$v?>"<?=($uvesos==1 ? ' checked' : '')?> />	
								<span class="form-check-sign"><?=$name_ru[$v]?></span>
							</label>
						</div>
						<? } else if ($v == 'mark') { ?>
						<div id="<?=$v?>_bl"<?=($fields["car_type"]=='прочее' ? ' style="display:none;"' : '')?>>
							<select class="form-control multiselect" id="<?=$v?>" onChange="chooseCar('mark')"<?=($mark_option=='' ? $dis_field[$v] : '')?>>
								<? if ($mark_option == '') { ?>
								<option value=""> сперва выберите Тип автомобиля </option>						
								<? } else echo $mark_option; ?>
							</select>
						</div>
						<input type="text" class="form-control" id="<?=$v?>_input" value="<?=$fields[$v]?>"<?=($fields["car_type"]=='прочее' ? '' : ' style="display:none;"')?> />
						<? } else if ($v == 'com_name') { ?>
						<div id="<?=$v?>_bl"<?=($fields["car_type"]=='прочее' ? ' style="display:none;"' : '')?>>
							<select class="form-control multiselect" id="<?=$v?>" onChange="chooseCar('com_name')"<?=($com_name_option=='' ? $dis_field[$v] : '')?>>
								<? if ($com_name_option == '') { ?>
								<option value=""> сперва выберите Марку </option>		
								<? } else echo $com_name_option; ?>							
							</select>
						</div>
						<input type="text" class="form-control" id="<?=$v?>_input" value="<?=$fields[$v]?>"<?=($fields["car_type"]=='прочее' ? '' : ' style="display:none;"')?> />
						<? } else if ($v == 'year') { ?>
						<div id="<?=$v?>_bl"<?=($fields["car_type"]=='прочее' ? ' style="display:none;"' : '')?>>
							<select class="form-control multiselect" id="<?=$v?>" onChange="chooseCar('year')"<?=($year_option=='' ? $dis_field[$v] : '')?>>
								<? if ($year_option == '') { ?>
								<option value=""> сперва выберите Коммерческое наименование </option>	
								<? } else echo $year_option; ?>
							</select>
						</div>
						<input type="text" class="form-control" id="<?=$v?>_input" value="<?=$fields[$v]?>"<?=($fields["car_type"]=='прочее' ? '' : ' style="display:none;"')?> />
						<? } else if ($v == 'volume') { ?>
						<div id="<?=$v?>_bl"<?=($fields["car_type"]=='прочее' ? ' style="display:none;"' : '')?>>
							<select class="form-control multiselect" id="<?=$v?>" onChange="chooseCar('volume')"<?=($volume_option=='' ? $dis_field[$v] : '')?>>
								<? if ($volume_option == '') { ?>
								<option value=""> сперва выберите Год выпуска </option>							
								<? } else echo $volume_option; ?>
							</select>
						</div>
						<input type="text" class="form-control" id="<?=$v?>_input" value="<?=$fields[$v]?>"<?=($fields["car_type"]=='прочее' ? '' : ' style="display:none;"')?> />
						<? } else { ?>
							<? if ($type_field[$v] == 'input') { ?>
                        <input type="text" class="form-control<?=($v=='user_phone' ? ' phone' : '').($v=='date_issue' ? ' dateInput' : '').($v=='user_iin' ? ' only_int' : '').($v=='vin' ? ' vin' : '')?>"<?=($v=='vin' ? ' maxlength="17"' : '').($v=='user_iin' ? ' maxlength="12"' : '')?> id="<?=$v?>" value="<?=$fields[$v]?>"<?=$dis_field[$v]?> />
							<? } else { ?>
						<textarea class="form-control" id="<?=$v?>"<?=$dis_field[$v]?>><?=str_replace('\n', '&#13;', $fields[$v])?></textarea>
							<? } ?>
						<? } ?>
                        <span class="control__help" id="error_<?=$v?>"></span>
                    </div>
					<? if ($v == 'car_type') { ?>
					<div class="col-lg-1 col-md-1 col-sm-1">
						<span class="loading" id="load_<?=$v?>"><img src="/library/img/load.gif" /></span>
					</div>
					<? } ?>
                </div>												
			
				<? 
				// СПИСОК ДЛЯ ВЫБОРКИ КОМПАНИИ							 
				if ($v == 'user_mail') { ?>
				<div class="form-group form-show-validation row"<?=($api->Managers->man_block == 4 ? ' style="display:none"' : '')?>>
                    <label for="id_broker" class="col-lg-4 col-md-3 col-sm-4 mt-sm-2 text-right">
						Компания <span class="required-label">*</span>
					</label>
                    <div class="col-lg-5 col-md-9 col-sm-8">						
						<select class="form-control" id="id_broker">							
							<?
							$sql_c = "";
							if ($api->Managers->man_block == 4)
								$sql_c = " AND `id`='".$api->Managers->man_id."'";
							else
								echo '<option value=""> выберите компанию </option>';
								
							$sCom=mysql_query("SELECT * FROM `i_manager_users` WHERE `id_section`=4".$sql_c." ORDER BY `active` DESC, `name` ASC");
							if (mysql_num_rows($sCom) > 0)
							{
								while($rCom=mysql_fetch_array($sCom))
								{									
									if ($api->Managers->man_block == 4)		
										$id_company = $api->Managers->man_id;																							
									
									echo '<option value="'.$rCom["id"].'"'.($id_company == $rCom["id"] ? ' selected' : '').'> '.$rCom["name"].(intval($rCom["active"]) == 1 ? '' : ' (блок)').' </option>';
								}
							}
							?>
						</select>
					</div>
					<span class="control__help" id="error_id_broker"></span>
				</div>
				<? } ?>
				
				<? if ($v == 'uvesos' || $v == 'dop_obor' || $v == 'vin') { ?>
			</div>
				<? } ?>
				
				<? if ($v == 'vin') { ?>
				<?
			if (!isset($_GET["edit"]))
			{
				$foto_name = Array();
				$foto_name[1] = 'ТЕХПАСПОРТ СПЕРЕДИ';
				$foto_name[2] = 'ТЕХПАСПОРТ СЗАДИ';
				$foto_name[3] = 'ИНВОЙС';
				$foto_name[4] = 'СОПРОВОДИТЕЛЬНЫЕ ДОКУМЕНТЫ';
				$foto_name[5] = 'УДОСТОВЕРЕНИЕ ЛИЧНОСТИ СПЕРЕДИ';
				$foto_name[6] = 'УДОСТОВЕРЕНИЕ ЛИЧНОСТИ СЗАДИ';
				$foto_name[7] = 'ПРОЧИЕ';
				$foto_name[8] = 'ПРОЧИЕ';
				$foto_name[9] = 'ПРОЧИЕ';
				$foto_name[10] = 'ПРОЧИЕ';		
				
				?>
			<div class="card-header" style="border-top: 1px solid #ebecec !important;">
				<div class="card-title">Фото Заявителя</div>
			</div>
			<div class="card-body links_bl">
				<?				
				for($i=1; $i<=10; $i++)
				{
					?>
					<div class="form-group form-show-validation row">
						<label for="photo" class="col-lg-4 col-md-4 col-sm-4 mt-sm-1 text-right">
							<?=$foto_name[$i]?><? //($i<=4?' <span class="required-label">*</span>':'')?>
						</label>
						<div class="col-lg-8 col-md-8 col-sm-8">
							<div class="fileUpload" id="add_foto<?=$i?>">
								<button type="button" class="btn btn-success load_">Загрузить</button>
								<div class="fileUpload-outerWrap">
									<div class="fileUpload-innerWrap">
										<iframe id="uploadFrame<?=$i?>" name="uploadFrame<?=$i?>" style="display:none"></iframe>
										<form name="form" id="photo<?=$i?>" target="uploadFrame<?=$i?>" action="/order/broker_foto.php" method="post" enctype="multipart/form-data">
											<input class="fileUpload-input" name="img<?=$i?>" id="pac_img<?=$i?>" type="file" onchange="sub_f('<?=$i?>');"  />
											<input class="forms" type="hidden" name="image<?=$i?>" id="image<?=$i?>" />
											<input class="forms" type="hidden" name="count" id="count" value="<?=$i?>" />
										</form>
									</div>
								</div>
							</div>
							<div class="mt-1" id="delete_foto<?=$i?>" style="display:none">
								<span id="load_to_foto<?=$i?>"></span> – 
								<span class="link" onclick="deleteFoto('<?=$i?>')">Удалить</span>
							</div>
							<span class="control__help" id="error_image<?=$i?>"></span>
							<span id="loader<?=$i?>" style="display:none"><img src="/library/img/load.gif" /></span>
						</div>
					</div>	
				<? } ?>
				<div id="protocolDelete"></div>
			</div>
			<script type="text/javascript">
				
				function onResponseCab(d) 
				{  
					eval('var obj = ' + d + ';');
					if (obj.success) 
					{
						var type = obj.img;

						jQuery("#add_foto"+type).hide();
						jQuery("#delete_foto"+type).show();	
						jQuery("#load_to_foto"+type).html("<a href='/upload/foto_broker/"+obj.name+"' target='_blank'>"+obj.name+"</a>");
						jQuery("#image"+type).val(obj.name);
						jQuery("#loader"+obj.img).hide();
					}
					else { alert(obj.erorr); jQuery("#loader"+obj.img).hide(); jQuery("#add_foto"+obj.img).show(); jQuery('#pac_img'+obj.img).val(""); jQuery('#image'+obj.img).val(""); }
				}

				function sub_f(number) 
				{ 
					jQuery("#loader"+number).show(); 
					jQuery("#add_foto"+number).hide(); 
					jQuery("#error_image"+number).html('').hide();
					jQuery('#photo'+number).submit(); 
				}

				function deleteFoto(type)
				{
					var foto_name = Array();
					foto_name[1] = 'ТЕХПАСПОРТ СПЕРЕДИ';
					foto_name[2] = 'ТЕХПАСПОРТ СЗАДИ';
					foto_name[3] = 'ИНВОЙС';
					foto_name[4] = 'СОПРОВОДИТЕЛЬНЫЕ ДОКУМЕНТЫ';
					foto_name[5] = 'УДОСТОВЕРЕНИЕ ЛИЧНОСТИ СПЕРЕДИ';
					foto_name[6] = 'УДОСТОВЕРЕНИЕ ЛИЧНОСТИ СЗАДИ';
					foto_name[7] = 'ПРОЧИЕ';
					foto_name[8] = 'ПРОЧИЕ';
					foto_name[9] = 'ПРОЧИЕ';
					foto_name[10] = 'ПРОЧИЕ';					

					if (confirm("Вы действительно хотите удалить фото «"+foto_name[type]+"»?"))
					{
						jQuery.ajax({
							url: "ajax.php",
							data: "do=deleteFotoBroker&type="+type+"&foto="+jQuery("#image"+type).val()+"&x=secure",
							type: "POST",
							dataType : "html",
							cache: false,

							beforeSend: function()		{ jQuery("#delete_foto"+type).hide(); jQuery("#loader"+type).show(); },
							success:  function(data)  	{ jQuery("#protocolDelete").html(data); jQuery("#loader"+type).hide(); },
							error: function()         	{ jQuery("#protocolDelete").html("<p style='color:#f00'>Невозможно связаться с сервером!</p>"); jQuery("#delete_foto"+type).show(); jQuery("#loader"+type).hide(); }																		
						});
					}
				}
				
			</script>
				<?
			}
				?>
							
			<div class="card-action t-right" id="action">
				<a class="btn btn-warning" style="float:left" href="javascript:history.go(-1)">Вернуться назад</a>
                <button class="btn btn-success action" onclick="addZ();"><?=($can_edit == 1 ? 'Сохранить' : 'Добавить')?></button>
                <div class="loading">
                    <img src="/library/img/load.gif" /> Ваш запрос обрабатывается...
                </div>
                <div class="protocol_add"></div>
            </div>
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
				if (field == 'car_type' && jQuery("#car_type").val() == 'прочее')
				{
					jQuery("#mark_bl").hide();
					jQuery("#com_name_bl").hide();
					jQuery("#year_bl").hide();
					jQuery("#volume_bl").hide();
					
					jQuery("#mark_input").show();
					jQuery("#com_name_input").show();
					jQuery("#year_input").show();
					jQuery("#volume_input").show();
				}
				else
				{
					jQuery("#mark_bl").show();
					jQuery("#com_name_bl").show();
					jQuery("#year_bl").show();
					jQuery("#volume_bl").show();
					
					jQuery("#mark_input").hide();
					jQuery("#com_name_input").hide();
					jQuery("#year_input").hide();
					jQuery("#volume_input").hide();
					
					if (err_key == 0)
					{
						jQuery.ajax(
						{
							url: "ajax.php",
							data: "do=chooseCar&field="+field+"&car_type="+jQuery("#car_type").val()+"&mark="+jQuery("#mark").val()+"&com_name="+jQuery("#com_name").val()+"&volume="+jQuery("#volume").val()+"&year="+jQuery("#year").val()+"&x=secure",
							type: "POST",
							dataType : "html",
							cache: false,

							beforeSend: function()		{ jQuery(".protocol_add").html("");  jQuery("#load_"+field).show(); },
							success:  function(data)	{ jQuery(".protocol_add").html(data); jQuery("#load_"+field).hide(); },
							error: function()			{ alert("Невозможно связаться с сервером"); jQuery("#load_"+field).hide(); }
						});
					}
				}
			}
			
			function addZ()
			{
				var err_key = 0;
				var focused = 0;

				jQuery(".card-body input").css("border-color", "#c9cbcd");
				jQuery(".card-body textarea").css("border-color", "#c9cbcd");
				jQuery(".control__help").html('').hide();
												
				<? foreach($mandat as $k=>$v) { ?>
					<? if ($k=='mark' || $k=='com_name' || $k=='year' || $k=='volume') { ?>
				if (jQuery("#<?=$k?>").val() == '' && jQuery("#car_type").val() != 'прочее')
				{
					err_key = 1;
					jQuery("#<?=$k?>").css("border-color", "#f00");
					jQuery("#error_<?=$k?>").html('Не заполнено поле <?=$name_ru[$k]?>').css("display", "inline-block");
					if (focused == 0) { jQuery("#<?=$k?>").focus(); focused = 1; }
				}
				
				if (jQuery("#<?=$k?>_input").val() == '' && jQuery("#car_type").val() == 'прочее')
				{
					err_key = 1;
					jQuery("#<?=$k?>_input").css("border-color", "#f00");
					jQuery("#error_<?=$k?>").html('Не заполнено поле <?=$name_ru[$k]?>').css("display", "inline-block");
					if (focused == 0) { jQuery("#<?=$k?>_input").focus(); focused = 1; }
				}
					<? } else { ?>
						<? if ($v == 1 && $k != 'user_phone' && $k != 'vin') { ?>
				if (jQuery("#<?=$k?>").val() == '')
				{
					err_key = 1;
					jQuery("#<?=$k?>").css("border-color", "#f00");
					jQuery("#error_<?=$k?>").html('Не заполнено поле <?=$name_ru[$k]?>').css("display", "inline-block");
					if (focused == 0) { jQuery("#<?=$k?>").focus(); focused = 1; }
				}
						<? } ?>
					<? } ?>
				<? } ?>												
				
				var iin = jQuery("#user_iin").val();
				if (jQuery("#user_iin").val()=="" || iin.length != 12)
				{
					err_key = 1;					
					jQuery("#user_iin").css("border-color", "#f00");
					jQuery("#error_user_iin").html("Не заполнено поле ИИН").css("display", "inline-block");;
					if (focused == 0) { jQuery("#user_iin").focus(); focused = 1; }
				}
				
				var vin = jQuery("#vin").val();
				if (jQuery("#vin").val()=="" || vin.length != 17)
				{
					err_key = 1;					
					jQuery("#vin").css("border-color", "#f00");
					jQuery("#error_vin").html("Не заполнено поле VIN").css("display", "inline-block");;
					if (focused == 0) { jQuery("#vin").focus(); focused = 1; }
				}
				
				var phone = jQuery("#user_phone").val();
				phone = phone.replace(/_/g, "");
				if (jQuery("#user_phone").val()=="" || phone.length != 14)
				{
					err_key = 1;					
					jQuery("#user_phone").css("border-color", "#f00");
					jQuery("#error_user_phone").html("Не заполнено поле Телефон").css("display", "inline-block");;
					if (focused == 0) { jQuery("#user_phone").focus(); focused = 1; }
				}
				
				if (jQuery("#user_mail").val().trim() != "" && EmailCheck(jQuery("#user_mail").val().trim()) == false)
				{
					err_key = 1;					
					jQuery("#user_mail").css("border-color", "#f00");
					jQuery("#error_user_mail").html("Не заполнено поле E-mail").css("display", "inline-block");;
					if (focused == 0) { jQuery("#user_mail").focus(); focused = 1; }
				}
				
				<? if ($api->Managers->man_block == 1 || $api->Managers->man_block == 2 || $api->Managers->man_block == 5) { ?>
				if (jQuery("#id_broker").val()=="")
				{
					err_key = 1;					
					jQuery("#id_broker").css("border-color", "#f00");
					jQuery("#error_id_broker").html("Не выбрана Компания").css("display", "inline-block");;
					if (focused == 0) { jQuery("#id_broker").focus(); focused = 1; }
				}
				<? } ?>
				
				var uvesos = 0;
				if (jQuery("#uvesos").prop("checked") == true)	{ uvesos = 1; }
				
				var images = '';
				<? if (!isset($_GET["edit"])) { ?>
					<?php /*?><? for($i=1; $i<=4; $i++) { ?>
				if (jQuery("#image<?=$i?>").val()=="")
				{
					err_key = 1;										
					jQuery("#error_image<?=$i?>").html("Не загружено фото <?=$foto_name[$i]?>").css("display", "inline-block");
					if (focused == 0) { jQuery("#pac_img<?=$i?>").focus(); focused = 1; }
				}	
					<? } ?><?php */?>
					<? for($i=1; $i<=10; $i++) { ?>
				images = images + "&image<?=$i?>="+jQuery("#image<?=$i?>").val();
					<? } ?>
				<? } ?>
				
				if (err_key == 0)
				{
					jQuery.ajax(
					{
						url: "ajax.php",
						data: "do=<?=($can_edit == 1 ? 'edit&edit='.intval($_GET["edit"]) : 'add')?>&id_broker="+jQuery("#id_broker").val()+"&uvesos="+uvesos+images+"&x=secure"<?=$java?>,
						type: "POST",
						dataType : "html",
						cache: false,

						beforeSend: function()		{ jQuery(".protocol_add").html(""); jQuery(".action").hide(); jQuery(".loading").show(); },
						success:  function(data)	{ jQuery(".protocol_add").html(data); jQuery(".loading").hide(); },
						error: function()			{ alert("Невозможно связаться с сервером"); jQuery(".action").show(); jQuery(".loading").hide(); }
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
