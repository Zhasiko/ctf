<?
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
$name_ru["dlina"] = ' -  длина';
$name_ru["shirina"] = ' -  ширина';
$name_ru["vysota"] = ' -  высота';
$name_ru["baza_mm"] = 'База, мм';
$name_ru["koleya"] = 'Колея передних/задних колес, мм';
$name_ru["gibrid"] = 'Описание гибридного транспортного средства';
$name_ru["dvigatel"] = 'Двигатель внутреннего сгорания (марка, тип)';
$name_ru["count_cilindr"] = ' - количество и расположение цилиндров';
$name_ru["obem_cilindr"] = ' - рабочий объем цилиндров, см3';
$name_ru["stepen_sj"] = ' - степень сжатия';
$name_ru["max_mosh"] = ' - максимальная мощность, кВт (мин.-1)';
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
$name_ru["podveska_pered"] = ' - передняя';
$name_ru["podveska_zad"] = ' - задняя';
$name_ru["rul_upravl"] = 'Рулевое управление (марка, тип)';
$name_ru["tormoz"] = 'Тормозные системы (тип)';
$name_ru["tormoz_rab"] = ' -  рабочая';
$name_ru["tormoz_zapas"] = ' - запасная';
$name_ru["tormoz_sto"] = ' - стояночная';
$name_ru["shiny"] = 'Шины';
$name_ru["dop_obor"] = 'Дополнительное оборудование транспортного средства';

$style_td_left = 'width:40%; padding:2px; border:1px solid #000; border-collapse:collapse; font-size:11px; line-height:16px;';
$style_td_right = 'width:60%; padding:2px; border:1px solid #000; border-collapse:collapse; font-size:11px; line-height:16px;';

$user_ = Array();
$user_[1] = 'ИЛ ТОО ""Евро-Тест"" РК, Алматинская область, Карасайский район, Жамбылский с.о., село Улан,'; 
$user_[2] = 'РК, город Алматы, Медеуский район, улица Крымская, дом 50 тел/факс 87475760533'; 
$user_[3] = 'euro.test07@mail.ru № KZ.T.02.E0603  до 21.10.2026 г.';

if ($info_temp!='')
{	
	$x_name = Array();
	$len = mb_strlen($info_temp, 'UTF-8');
	for ($i = 0; $i < $len; $i++) {
		$x_name[] = mb_substr($info_temp, $i, 1, 'UTF-8');
	}

	$user_ = Array();
	$user_[1] = ''; $user_[2] = ''; $user_[3] = '';
	$xx = 1;
	foreach($x_name as $k_=>$v_)
	{
		@$user_[$xx] .= $v_;
		if ((($k_+1) % 90) == 0)	$xx++;
	}
}

$strPdf = '
<div style="position:relative; width:590px; height:1080px; font-family:Microsoft Sans Serif; margin:0 0 0 110px; padding-top:170px">
	<h2 style="text-align:center; font-weight:500; font-size:15px; line-height:20px; margin-bottom:15px; letter-spacing: -1px;">'.stripslashes($r["pdf_num"]).'</h2>
	<h2 style="text-align:center; font-weight:500; font-size:15px; line-height:20px; margin-bottom:10px;">ИСПЫТАТЕЛЬНАЯ РАБОТА</h2>
	<div style="font-size:12px; line-height:12px; text-align:center; padding:0 0 1px; margin:0 0 1px; border-bottom:1px solid #000; position:relative">'.$user_[1].'</div>
	<div style="font-size:7px; margin:0 0 5px; text-align:center;">(полное и сокращенное наименование,</div>
	<div style="font-size:12px; line-height:12px; text-align:center; padding:0 0 1px; margin:0 0 1px; border-bottom:1px solid #000; position:relative">'.$user_[2].'</div>
	<div style="font-size:7px; margin:0 0 5px; text-align:center;">адрес, номер, окончание срока</div>
	<div style="font-size:12px; line-height:12px; text-align:center; padding:0 0 1px; margin:0 0 1px; border-bottom:1px solid #000; position:relative">'.$user_[3].'</div>
	<div style="font-size:7px; margin:0 0 5px; text-align:center;">действия аттестата аккредитации)</div>
	<div style="font-size:12px; line-height::22px; text-align:center; margin:5px 0">ТРАНСПОРТНОЕ СРЕДСТВО</div>
	<table style="width:100%;" border="1" cellpadding="0" cellspacing="0.5">
		<tr>
			<td style="'.$style_td_left.'" align="left" valign="middle">МАРКА</td>
			<td style="'.$style_td_right.'" align="left" valign="middle">'.stripslashes($r["mark"]).'</td>
		</tr>	
		<tr>
			<td style="'.$style_td_left.'" align="left" valign="middle">КОММЕРЧЕСКОЕ НАИМЕНОВАНИЕ</td>
			<td style="'.$style_td_right.'" align="left" valign="middle">'.$com_name_new.'</td>
		</tr>	
		<tr>
			<td style="'.$style_td_left.'" align="left" valign="middle">ТИП</td>
			<td style="'.$style_td_right.'" align="left" valign="middle">'.stripslashes($r["type"]).'</td>
		</tr>	
		<tr>
			<td style="'.$style_td_left.'" align="left" valign="middle">ШАССИ</td>
			<td style="'.$style_td_right.'" align="left" valign="middle">'.stripslashes($r["chassis"]).'</td>
		</tr>	
		<tr>
			<td style="'.$style_td_left.'" align="left" valign="middle">ИДЕНТИФИКАЦИОННЫЙ НОМЕР (VIN)</td>
			<td style="'.$style_td_right.'" align="left" valign="middle">'.stripslashes($r["vin"]).'</td>
		</tr>
		<tr>
			<td style="'.$style_td_left.'" align="left" valign="middle">ГОД ВЫПУСКА</td>
			<td style="'.$style_td_right.'" align="left" valign="middle">'.stripslashes($r["year"]).'</td>
		</tr>
		<tr>
			<td style="'.$style_td_left.'" align="left" valign="middle">ОБЪЕМ</td>
			<td style="'.$style_td_right.'" align="left" valign="middle">'.stripslashes($r["volume"]).'</td>
		</tr>
		<tr>
			<td style="'.$style_td_left.'" align="left" valign="middle">КАТЕГОРИЯ</td>
			<td style="'.$style_td_right.'" align="left" valign="middle">'.stripslashes($r["category"]).'</td>
		</tr>
		<tr>
			<td style="'.$style_td_left.'" align="left" valign="middle">ЭКОЛОГИЧЕСКИЙ КЛАСС</td>
			<td style="'.$style_td_right.'" align="left" valign="middle">'.stripslashes($r["ek_class"]).'</td>
		</tr>
		<tr>
			<td style="'.$style_td_left.'" align="left" valign="middle">ЗАЯВИТЕЛЬ И ЕГО АДРЕС</td>
			<td style="'.$style_td_right.'" align="left" valign="middle">'.stripslashes($r["user_name"]).'</td>
		</tr>
		<tr>
			<td style="'.$style_td_left.'" align="left" valign="middle">- юридический адрес</td>
			<td style="'.$style_td_right.'" align="left" valign="middle">'.$api->Strings->pr_plus(htmlspecialchars_decode(stripslashes($r["user_uyr_adres"]))).'</td>
		</tr>
		<tr>
			<td style="'.$style_td_left.'" align="left" valign="middle">- фактический адрес</td>
			<td style="'.$style_td_right.'" align="left" valign="middle">'.$api->Strings->pr_plus(htmlspecialchars_decode(stripslashes($r["user_fac_adres"]))).'</td>
		</tr>
		<tr>
			<td style="'.$style_td_left.'" align="left" valign="middle">- телефон, факс</td>
			<td style="'.$style_td_right.'" align="left" valign="middle">'.stripslashes($r["user_phone"]).($r["user_fax"]!='' ? ', факс: '.stripslashes($r["user_fax"]) : '').'</td>
		</tr>
		<tr>
			<td style="'.$style_td_left.'" align="left" valign="middle">- адрес электронный</td>
			<td style="'.$style_td_right.'" align="left" valign="middle">'.stripslashes($r["user_mail"]).'</td>
		</tr>
		<tr>
			<td style="'.$style_td_left.'" align="left" valign="middle">ИЗГОТОВИТЕЛЬ И ЕГО АДРЕС</td>
			<td style="'.$style_td_right.'" align="left" valign="middle">'.$api->Strings->pr_plus(htmlspecialchars_decode(stripslashes($r["manufacturer"]))).'</td>
		</tr>
		<tr>
			<td style="'.$style_td_left.'" align="left" valign="middle">- юридический  адрес</td>
			<td style="'.$style_td_right.'" align="left" valign="middle">'.$api->Strings->pr_plus(htmlspecialchars_decode(stripslashes($r["manufacturer_uyr_adres"]))).'</td>
		</tr>
		<tr>
			<td style="'.$style_td_left.'" align="left" valign="middle">- фактический адрес</td>
			<td style="'.$style_td_right.'" align="left" valign="middle">'.$api->Strings->pr_plus(htmlspecialchars_decode(stripslashes($r["manufacturer_fac_adres"]))).'</td>
		</tr>
		<tr>
			<td style="'.$style_td_left.'" align="left" valign="middle">СБОРОЧНЫЙ ЗАВОД И ЕГО АДРЕС</td>
			<td style="'.$style_td_right.'" align="left" valign="middle">'.$api->Strings->pr_plus(htmlspecialchars_decode(stripslashes($r["zavod"]))).'</td>
		</tr>
		<tr>
			<td style="'.$style_td_left.'" align="left" valign="middle">- адреса</td>
			<td style="'.$style_td_right.'" align="left" valign="middle">'.$api->Strings->pr_plus(htmlspecialchars_decode(stripslashes($r["zavod_adres"]))).'</td>
		</tr>
	</table>
	<div style="font-size:12px; line-height::22px; text-align:center; margin:5px 0">ОБЩИЕ ХАРАКТЕРИСТИКИ ТРАНСПОРТНОГО СРЕДСТВА</div>
	<table style="width:100%;" border="1" cellpadding="0" cellspacing="0.5">
	';

	$fields_1 = Array('wheels', 'privod', 'engine_location', 'bode_type', 'count_place', 'boot_space');

	foreach($fields_1 as $k=>$v)
	{
		$strPdf .= '
		<tr>
			<td style="'.$style_td_left.'" align="left" valign="middle">'.$name_ru[$v].'</td>
			<td style="'.$style_td_right.'" align="left" valign="middle">'.($r[$v]!='' ? $api->Strings->pr_plus(htmlspecialchars_decode(stripslashes($r[$v]))) : '-').'</td>
		</tr>
		';
	}

	$strPdf .= '
	</table>';

$strPdf .= '
</div>
<div style="position:relative; width:590px; height:1080px; font-family:Microsoft Sans Serif; margin:0 0 0 110px; padding-top:90px">
	<div style="font-size:12px; line-height:12px; padding:10px 0 0 360px;">Серия KZ № '.stripslashes($r["pdf_seriya"]).'</div>
	<div style="font-size:12px; line-height:12px; padding:20px 0 0 500px;">2</div>
	<h2 style="font-weight:500; font-size:15px; line-height:20px; margin:5px 0 30px 250px; letter-spacing: -1px;">'.stripslashes($r["pdf_num"]).'</h2>	
	<table style="width:100%;" border="1" cellpadding="0" cellspacing="0.5">
';

	$fields_2 = Array('cabina', 'count_pass', 'bagajnik', 'count_place_m2', 'rama', 'count_koles', 'massa', 'max_massa', 'dlina', 'shirina', 'vysota', 'baza_mm', 'koleya', 'gibrid', 'dvigatel', 'count_cilindr', 'obem_cilindr', 'stepen_sj', 'max_mosh', 'toplivo', 'sys_pitanie', 'sys_zajig', 'sys_gaz', 'elektro', 'rab_napr', 'max_30mosh', 'nakop_energ');

	foreach($fields_2 as $k=>$v)
	{
		if ($v == 'dlina')
		{
			$strPdf .= '
			<tr>
				<td style="'.$style_td_left.'" align="left" valign="middle">Габаритные размеры, мм</td>
				<td style="'.$style_td_right.'" align="left" valign="middle"></td>
			</tr>
			';			
		}
		
		$strPdf .= '
		<tr>
			<td style="'.$style_td_left.'" align="left" valign="middle">'.$name_ru[$v].'</td>
			<td style="'.$style_td_right.'" align="left" valign="middle">'.($r[$v]!='' ? $api->Strings->pr_plus(htmlspecialchars_decode(stripslashes($r[$v]))) : '-').'</td>
		</tr>
		';
	}

$strPdf .= '
	</table>
</div>
<div style="position:relative; width:590px; height:1080px; font-family:Microsoft Sans Serif; margin:0 0 0 110px; padding-top:90px">
	<div style="font-size:12px; line-height:12px; padding:10px 0 0 360px;">Серия KZ № '.stripslashes($r["pdf_seriya"]).'</div>
	<div style="font-size:12px; line-height:12px; padding:20px 0 0 500px;">3</div>
	<h2 style="font-weight:500; font-size:15px; line-height:20px; margin:5px 0 30px 250px; letter-spacing: -1px;">'.stripslashes($r["pdf_num"]).'</h2>		
	<table style="width:100%;" border="1" cellpadding="0" cellspacing="0.5">
	';		
	
	$fields_3 = Array('transmisiya', 'elek_marka', 'rab_napr2', 'max_30mosh2', 'ceplenie', 'korobka', 'podveska_pered', 'podveska_zad', 'rul_upravl', 'tormoz', 'tormoz_rab', 'tormoz_zapas', 'tormoz_sto', 'shiny', 'dop_obor');
	
	foreach($fields_3 as $k=>$v)
	{
		if ($v == 'podveska_pered')
		{
			$strPdf .= '
			<tr>
				<td style="'.$style_td_left.'" align="left" valign="middle">Подвеска(тип)</td>
				<td style="'.$style_td_right.'" align="left" valign="middle"></td>
			</tr>
			';			
		}
		/*
		if ($v == 'tormoz_rab')
		{
			$strPdf .= '
			<tr>
				<td style="'.$style_td_left.'" align="left" valign="middle">Тормозные системы (тип)</td>
				<td style="'.$style_td_right.'" align="left" valign="middle"></td>
			</tr>
			';			
		}
		*/
		$strPdf .= '
		<tr>
			<td style="'.$style_td_left.'" align="left" valign="middle">'.$name_ru[$v].'</td>
			<td style="'.$style_td_right.'" align="left" valign="middle">'.($r[$v]!='' ? $api->Strings->pr_plus(htmlspecialchars_decode(stripslashes($r[$v]))) : '-').'</td>
		</tr>
		';
	}	

$strPdf .= '
	</table>
	<div style="font-size:12px; line-height:12px; padding:5px 0 20px;">соответсвует требованиям технического регламента Таможенного союза "О безопасности колесных транспортных средств".</div>
	<table style="width:100%;" cellpadding="0" cellspacing="0">
		<tr>
			<td style="width:40%" align="left" valign="middle"><span style="font-size:12px; line-height:12px;">ДОПОЛНИТЕЛЬНАЯ ИНФОРМАЦИЯ</span></td>
			<td style="60%" align="left" valign="middle"></td>
		</tr>
		<tr>
			<td style="width:40%" align="left" valign="middle"></td>
			<td style="50%" align="left" valign="middle">
				<p style="font-size:12px; line-height:12px;">
					<span style="font-size:12px; line-height:12px;">Дата оформления</span> &nbsp; 
					<span style="padding:0 15px 5px; display:inline-block; border-bottom:1px solid #000">'.$api->Strings->date('ru',$date_oform,'sql','datetext').'</span>
				</p>
			</td>
		</tr>
	</table>
	<table style="width:100%; margin:30px 0 0" cellpadding="0" cellspacing="0">
		<tr>
			<td style="width:33%"><strong style="font-size:12px; line-height:12px;">Руководитель<br /> испытательной лаборотории</strong></td>
			<td style="width:33%; text-align:center;">
				<p style="font-size:12px; line-height:15px; text-align:center;">&nbsp;</p>
				<p>__________________________</p>
				<p style="font-size:7px; text-align:center; margin:3px 0 0;">(подпись)</p>
			</td>
			<td style="width:33%; text-align:center;">
				<p style="font-size:12px; line-height:15px; text-align:center;">'.$director.'</p>
				<p>__________________________</p>
				<p style="font-size:7px; text-align:center; margin:3px 0 0;">(инциалы, фамилия)</p>
			</td>
		</tr>
	</table>
</div>
';
?>
