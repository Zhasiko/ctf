<?
$lang="ru";
$title="Добавить запись";
if (isset($_GET["edit"]) && intval($_GET["edit"])!=0)
	$title="Редактировать запись";
$keywords="";
$description="";
require($_SERVER["DOCUMENT_ROOT"]."/libs/header.php");
if ($api->Managers->check_auth() == true)
{
	if ($api->Managers->man_block == 1 || $api->Managers->man_block == 2)
	{
		?>
		<div class="card">
            <div class="card-body">
				<?
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
				
				$fields = Array(); $type_field = Array(); $mandat = Array(); $class = Array(); $java = '';
				foreach($columns as $k=>$v)
				{
					$fields[$v] = '';
					$type_field[$v] = 'textarea';
					$mandat[$v] = 0;
					$class[$v] = '';
					
					$java .= '+"&'.$v.'="+jQuery("#'.$v.'").val()';
				}				
				
                if (
					(isset($_GET["edit"]) && intval($_GET["edit"]) != 0) ||
					(isset($_GET["copy"]) && intval($_GET["copy"]) != 0)
				)
                {
					$id = intval($_GET["edit"]);
					if (isset($_GET["copy"]) && intval($_GET["copy"]) != 0)
						$id = intval($_GET["copy"]);
					
                    $s=mysql_query("SELECT * FROM `i_baza` WHERE `id`='".$id."' LIMIT 1");
                    if (mysql_num_rows($s) > 0)
                    {
                        $r=mysql_fetch_array($s);

                        foreach($columns as $k=>$v)				
							$fields[$v] = stripslashes($api->Strings->pr_plus($r[$v]));
                    }
                }
		
				$name_ru = Array();
				$name_ru["car_type"] = 'ТИП АВТОМОБИИЛЯ';
				$name_ru["mark"] = 'МАРКА';
				$name_ru["com_name"] = 'КОММЕРЧЕСКОЕ НАИМЕНОВАНИЕ';
				$name_ru["year"] = 'ГОД ВЫПУСКА';
				$name_ru["volume"] = 'ОБЪЁМ';
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
						
				$mandat["car_type"] = 1;
				$mandat["mark"] = 1;
				$mandat["com_name"] = 1;			
				$mandat["year"] = 1;
				//$mandat["volume"] = 1;
		
				$class["year"] = ' only_int';
				$class["volume"] = ' only_float';
		
				$type_field["mark"] = 'input';
				$type_field["com_name"] = 'input';
				$type_field["year"] = 'input';
				$type_field["volume"] = 'input';
				$type_field["type"] = 'input';
				$type_field["category"] = 'input';
				$type_field["ek_class"] = 'input';			
                ?>                
                				
				<? foreach($columns as $k=>$v) { ?>
				<div class="form-group form-show-validation row">
                    <label for="name" class="col-lg-4 col-md-3 col-sm-4 mt-sm-2 text-right"><?=$name_ru[$v]?> <?=($mandat[$v]==1?'<span class="required-label">*</span>':'')?></label>
                    <div class="col-lg-5 col-md-9 col-sm-8">
						<? if ($v == 'car_type') { ?>
						<select class="form-control" id="<?=$v?>">							                        	
							<option value="легковой автомобиль"<?=($fields[$v] == 'легковой автомобиль' ? ' selected' : '')?>> легковой автомобиль </option>
							<option value="грузовой автомобиль"<?=($fields[$v] == 'грузовой автомобиль' ? ' selected' : '')?>> грузовой автомобиль </option>
							<option value="седельный тягач"<?=($fields[$v] == 'седельный тягач' ? ' selected' : '')?>> седельный тягач </option>
						</select>
						<? } else { ?>
							<? if ($type_field[$v] == 'input') { ?>
                        <input type="text" class="form-control<?=$class[$v]?>" id="<?=$v?>" value="<?=$fields[$v]?>" />
							<? } else { ?>
						<textarea class="form-control" id="<?=$v?>"><?=$fields[$v]?></textarea>
							<? } ?>
						<? } ?>
                        <span class="control__help" id="error_<?=$v?>"></span>
                    </div>
                </div>								
				<? } ?>
				
			</div>

            <div class="card-action t-right" id="action">				
            	<? if (isset($_GET["edit"]) && intval($_GET["edit"]) != 0) { ?>
                <button type="button" class="btn btn-danger action"  style="margin-right:50px" onclick="deleteZ();">Удалить</button>
                <? } else { ?>
				<a class="btn btn-warning action" style="float:left" href="javascript:history.go(-1)">Вернуться назад</a>
				<? } ?>
                <button class="btn btn-success action" onclick="addZ();"><?=(isset($_GET["edit"]) ? 'Сохранить' : 'Добавить')?></button>
                <div class="loading">
                    <img src="/library/img/load.gif" /> Ваш запрос обрабатывается...
                </div>
                <div id="protocol"></div>
            </div>

		</div>
		<script type="text/javascript">

			function addZ()
			{
				var err_key = 0;
				var focused = 0;

				jQuery(".calc__card input").css("border-color", "#c9cbcd");
				jQuery(".calc__card textarea").css("border-color", "#c9cbcd");
				jQuery(".control__help").html('').hide();

				<? foreach($mandat as $k=>$v) { ?>
					<? if ($v == 1) { ?>
				if (jQuery("#<?=$k?>").val() == '')
				{
					err_key = 1;
					jQuery("#<?=$k?>").css("border-color", "#f00");
					jQuery("#error_<?=$k?>").html('Не заполнено поле <?=$name_ru[$k]?>').css("display", "inline-block");
					if (focused == 0) { jQuery("#<?=$k?>").focus(); focused = 1; }
				}
					<? } ?>
				<? } ?>												

				if (err_key == 0)
				{
					jQuery.ajax(
					{
						url: "ajax.php",
						data: "do=<?=(isset($_GET["edit"]) && intval($_GET["edit"]) != 0 ? 'edit' : 'add')?>&x=secure<?=(isset($_GET["edit"]) && intval($_GET["edit"]) != 0 ? '&edit='.intval($_GET["edit"]) : '')?>"<?=$java?>,
						type: "POST",
						dataType : "html",
						cache: false,

						beforeSend: function()		{ jQuery("#protocol").html(""); jQuery(".action").hide(); jQuery(".loading").show(); },
						success:  function(data)	{ jQuery("#protocol").html(data); <?php /*?>jQuery(".action").show();<?php */?> jQuery(".loading").hide(); },
						error: function()			{ alert("Невозможно связаться с сервером"); jQuery(".action").show(); jQuery(".loading").hide(); }
					});
				}
			}

			<? if (isset($_GET["edit"]) && intval($_GET["edit"]) != 0) { ?>
			function deleteZ()
			{
				var err_key = 0;
				var focused = 0;

				if (confirm("Вы действительно хотите удалить запись?"))
				{
					jQuery.ajax(
					{
						url: "ajax.php",
						data: "do=delete&edit=<?=intval($_GET["edit"])?>&x=secure",
						type: "POST",
						dataType : "html",
						cache: false,

						beforeSend: function()		{ jQuery("#protocol").html(""); jQuery("#action").hide(); },
						success:  function(data)	{ jQuery("#protocol").html(data); jQuery("#action").show(); },
						error: function()			{ alert("Невозможно связаться с сервером"); jQuery("#action").show(); }
					});
				}
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
