<?
//include_once($_SERVER['DOCUMENT_ROOT']."/libs/mysql.php");
//include_once($_SERVER['DOCUMENT_ROOT']."/libs/api.php");

//$s=mysql_query("SELECT * FROM `i_order` WHERE `id`=39 LIMIT 1");
//$r=mysql_fetch_array($s);

//error_reporting(E_ALL);
//ini_set('display_errors', TRUE);
//ini_set('display_startup_errors', TRUE);

include_once($_SERVER["DOCUMENT_ROOT"].'/libs/phpexcel/Classes/PHPExcel.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/libs/phpexcel/Classes/PHPExcel/IOFactory.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/libs/phpexcel/Classes/PHPExcel/Writer/Excel2007.php');

$objPHPExcel = new PHPExcel();

$page = $objPHPExcel->setActiveSheetIndex(0);

$fields_ = Array(); 					$f=1;
$fields_[$f] = 'номер';					$f++;
$fields_[$f] = 'дата';					$f++;
$fields_[$f] = 'ФИО';					$f++;
$fields_[$f] = 'адрес';					$f++;
$fields_[$f] = 'свх';					$f++;
$fields_[$f] = 'марка';					$f++;
$fields_[$f] = 'модель';				$f++;
$fields_[$f] = 'тип';					$f++;
$fields_[$f] = 'вин';					$f++;
$fields_[$f] = 'категория';				$f++;
$fields_[$f] = 'дата выпуска ТС';		$f++;
$fields_[$f] = 'изготовитель';			$f++;
$fields_[$f] = 'сборочный';				$f++;
$fields_[$f] = 'масса без нагрузки';	$f++;
$fields_[$f] = 'масса с нагрузкой';		$f++;
$fields_[$f] = 'длинна';				$f++;
$fields_[$f] = 'ширина';				$f++;
$fields_[$f] = 'высота';				$f++;
$fields_[$f] = 'Колесная формула';		$f++;
$fields_[$f] = 'Схема компоновки';		$f++;
$fields_[$f] = 'Расположение двиг';		$f++;
$fields_[$f] = 'тип кузова';			$f++;
$fields_[$f] = 'колво мест';			$f++;
$fields_[$f] = 'исполнение загруз';		$f++;
$fields_[$f] = 'кабина';				$f++;
$fields_[$f] = 'двигатель';				$f++;
$fields_[$f] = 'цилиндры';				$f++;
$fields_[$f] = 'рабочий обьем';			$f++;
$fields_[$f] = 'степень сжатия';		$f++;
$fields_[$f] = 'макс мощность';			$f++;
$fields_[$f] = 'система питания';		$f++;
$fields_[$f] = 'система зажигания';		$f++;
$fields_[$f] = 'отработка газов';		$f++;
$fields_[$f] = 'трансмиссия';			$f++;
$fields_[$f] = 'коробка';				$f++;
$fields_[$f] = 'подвеска';				$f++;
$fields_[$f] = 'передняя';				$f++;
$fields_[$f] = 'задняя';				$f++;
$fields_[$f] = 'рул управление';		$f++;
$fields_[$f] = 'торм. Система';			$f++;
$fields_[$f] = 'рабочая';				$f++;
$fields_[$f] = 'запасная';				$f++;
$fields_[$f] = 'стояночная';			$f++;
$fields_[$f] = 'шины';					$f++;
$fields_[$f] = 'кнопка СОС';			$f++;
$fields_[$f] = 'топливо';		

$value_ = Array();															$v=1;
$value_[$v] = $r["pdf_seriya"];												$v++;
$value_[$v] = $api->Strings->date('ru',$r["create_date"],'sql','datetext');	$v++;
$value_[$v] = stripslashes($r["user_name"]);								$v++;
$value_[$v] = stripslashes($r["user_fac_adres"]);							$v++;
$value_[$v] = stripslashes($r["svh"]);										$v++;
$value_[$v] = stripslashes($r["mark"]);										$v++;
$value_[$v] = $com_name_new;												$v++;
$value_[$v] = stripslashes($r["type"]);										$v++;
$value_[$v] = stripslashes($r["vin"]);										$v++;
$value_[$v] = stripslashes($r["category"]);									$v++;
$value_[$v] = stripslashes($r["year"]).' г.в.';								$v++;
$value_[$v] = stripslashes($r["manufacturer"]);								$v++;
$value_[$v] = stripslashes($r["zavod"]);									$v++;
$value_[$v] = stripslashes($r["massa"]);									$v++;
$value_[$v] = stripslashes($r["max_massa"]);								$v++;
$value_[$v] = stripslashes($r["dlina"]);									$v++;
$value_[$v] = stripslashes($r["shirina"]);									$v++;
$value_[$v] = stripslashes($r["vysota"]);									$v++;
$value_[$v] = stripslashes($r["wheels"]);									$v++;
$value_[$v] = stripslashes($r["privod"]);									$v++;
$value_[$v] = stripslashes($r["engine_location"]);							$v++;
$value_[$v] = stripslashes($r["bode_type"]);								$v++;
$value_[$v] = stripslashes($r["count_place"]);								$v++;
$value_[$v] = stripslashes($r["boot_space"]);								$v++;
$value_[$v] = stripslashes($r["cabina"]);									$v++;
$value_[$v] = stripslashes($r["dvigatel"]);									$v++;
$value_[$v] = stripslashes($r["count_cilindr"]);							$v++;
$value_[$v] = stripslashes($r["obem_cilindr"]);								$v++;
$value_[$v] = stripslashes($r["stepen_sj"]);								$v++;
$value_[$v] = stripslashes($r["max_mosh"]);									$v++;
$value_[$v] = stripslashes($r["sys_pitanie"]);								$v++;
$value_[$v] = stripslashes($r["sys_zajig"]);								$v++;
$value_[$v] = stripslashes($r["sys_gaz"]);									$v++;
$value_[$v] = stripslashes($r["transmisiya"]);								$v++;
$value_[$v] = stripslashes($r["korobka"]);									$v++;
$value_[$v] = '';															$v++;
$value_[$v] = stripslashes($r["podveska_pered"]);							$v++;
$value_[$v] = stripslashes($r["podveska_zad"]);								$v++;
$value_[$v] = stripslashes($r["rul_upravl"]);								$v++;
$value_[$v] = '';															$v++;
$value_[$v] = stripslashes($r["tormoz_rab"]);								$v++;
$value_[$v] = stripslashes($r["tormoz_zapas"]);								$v++;
$value_[$v] = stripslashes($r["tormoz_sto"]);								$v++;
$value_[$v] = stripslashes($r["shiny"]);									$v++;
$value_[$v] = '';															$v++;	// кнопка СОС
$value_[$v] = stripslashes($r["toplivo"]);									

$value_["iin"] = $r["user_iin"];
$value_["ek_class"] = $r["ek_class"];
$value_["adres_TS"] = $r["manufacturer_uyr_adres"];
$value_["baza_mm"] = $r["baza_mm"];
$value_["koleya"] = $r["koleya"];

$stolbec_ = Array();
$stolbec_[1] = 'H';
$stolbec_[2] = 'H';
$stolbec_[3] = 'J';
$stolbec_[4] = 'J';
$stolbec_[5] = 'N';
$stolbec_[6] = 'E';
$stolbec_[7] = 'E';
$stolbec_[8] = 'G';
$stolbec_[9] = 'G';
$stolbec_[10] = 'G';
$stolbec_[11] = 'G';
$stolbec_[12] = 'H';
$stolbec_[13] = 'H';
$stolbec_[14] = 'H';
$stolbec_[15] = 'H';
$stolbec_[16] = 'H';
$stolbec_[17] = 'H';
$stolbec_[18] = 'H';
$stolbec_[19] = 'G';
$stolbec_[20] = 'G';
$stolbec_[21] = 'G';
$stolbec_[22] = 'G';
$stolbec_[23] = 'G';
$stolbec_[24] = 'H';
$stolbec_[25] = 'H';
$stolbec_[26] = 'J';
$stolbec_[27] = 'G';
$stolbec_[28] = 'G';
$stolbec_[29] = 'G';
$stolbec_[30] = 'G';
$stolbec_[31] = 'I';
$stolbec_[32] = 'G';
$stolbec_[33] = 'I';
$stolbec_[34] = 'G';
$stolbec_[35] = 'G';
$stolbec_[36] = 'H';
$stolbec_[37] = 'M';
$stolbec_[38] = 'M';
$stolbec_[39] = 'M';
$stolbec_[40] = 'H';
$stolbec_[41] = 'U';
$stolbec_[42] = 'H';
$stolbec_[43] = 'P';
$stolbec_[44] = 'G';
$stolbec_[45] = 'U';
$stolbec_[46] = 'G';

//echo '<pre>'; print_r($fields_); echo '</pre>';
//echo '<pre>'; print_r($value_); echo '</pre>';

$styleBorderBottom = array(		
	'borders' => array(	
		'bottom' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,
		)
	)
);

$styleBorder = array(		
	'borders' => array(
		'top' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,
		),
		'bottom' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,
		),
		'left' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,
		),
		'right' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,
		)
	)
);

$i=1;
foreach($fields_ as $k=>$v)
{	
	$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->applyFromArray($styleBorderBottom);
	$objPHPExcel->getActiveSheet()->getStyle('B'.$i)->applyFromArray($styleBorderBottom);
	$objPHPExcel->getActiveSheet()->getStyle('C'.$i)->applyFromArray($styleBorderBottom);
	$objPHPExcel->getActiveSheet()->setCellValue('A'.$i, str_replace('\n', '', $fields_[$i])); 	
		
	$objPHPExcel->getActiveSheet()->mergeCells('D'.$i.':'.$stolbec_[$i].$i);
	$objPHPExcel->getActiveSheet()->getStyle('D'.$i.':'.$stolbec_[$i].$i)->applyFromArray($styleBorder);
	$objPHPExcel->getActiveSheet()->setCellValue('D'.$i, str_replace('\n', '', @$value_[$i])); 	
	
	if (
		($i>=19 && $i<=23) ||
		($i>=27 && $i<=30) ||
		($i==32) ||
 		($i>=34 && $i<=35)		
	)
		$objPHPExcel->getActiveSheet()->getStyle('H'.$i)->applyFromArray($styleBorder);		
	
	$i++;
}																					

$j=3;
$objPHPExcel->getActiveSheet()->setCellValue('K'.$j, 'ИИН');
$objPHPExcel->getActiveSheet()->getStyle('K'.$j)->applyFromArray($styleBorder);
$objPHPExcel->getActiveSheet()->mergeCells('L'.$j.':N'.$j);
$objPHPExcel->getActiveSheet()->getStyle('L'.$j.':N'.$j)->applyFromArray($styleBorder);
$objPHPExcel->getActiveSheet()->setCellValue('L'.$j, $value_["iin"]); 	
$objPHPExcel->getActiveSheet()->getStyle('L'.$j)->getNumberFormat()->setFormatCode('0');

$j=11;
$objPHPExcel->getActiveSheet()->mergeCells('H'.$j.':I'.$j);
$objPHPExcel->getActiveSheet()->setCellValue('H'.$j, 'эколог класс');
$objPHPExcel->getActiveSheet()->getStyle('H'.$j.':I'.$j)->applyFromArray($styleBorder);
$objPHPExcel->getActiveSheet()->getStyle('J'.$j)->applyFromArray($styleBorder);
$objPHPExcel->getActiveSheet()->setCellValue('J'.$j, $value_["ek_class"]);

$j++;
$objPHPExcel->getActiveSheet()->setCellValue('I'.$j, 'Адрес ТС');
$objPHPExcel->getActiveSheet()->getStyle('I'.$j)->applyFromArray($styleBorder);
$objPHPExcel->getActiveSheet()->mergeCells('J'.$j.':O'.$j);
$objPHPExcel->getActiveSheet()->getStyle('J'.$j.':O'.$j)->applyFromArray($styleBorder);
$objPHPExcel->getActiveSheet()->setCellValue('J'.$j, $value_["adres_TS"]);

$j=18;
$objPHPExcel->getActiveSheet()->mergeCells('I'.$j.':J'.$j);
$objPHPExcel->getActiveSheet()->setCellValue('I'.$j, 'База, мм');
$objPHPExcel->getActiveSheet()->getStyle('I'.$j.':J'.$j)->applyFromArray($styleBorder);
$objPHPExcel->getActiveSheet()->mergeCells('K'.$j.':N'.$j);
$objPHPExcel->getActiveSheet()->getStyle('K'.$j.':N'.$j)->applyFromArray($styleBorder);
$objPHPExcel->getActiveSheet()->setCellValue('K'.$j, $value_["baza_mm"]);

$j++;
$objPHPExcel->getActiveSheet()->mergeCells('I'.$j.':J'.$j);
$objPHPExcel->getActiveSheet()->setCellValue('I'.$j, 'Колея п/p колес');
$objPHPExcel->getActiveSheet()->getStyle('I'.$j.':J'.$j)->applyFromArray($styleBorder);
$objPHPExcel->getActiveSheet()->mergeCells('K'.$j.':N'.$j);
$objPHPExcel->getActiveSheet()->getStyle('K'.$j.':N'.$j)->applyFromArray($styleBorder);
$objPHPExcel->getActiveSheet()->setCellValue('K'.$j, $value_["koleya"]);

$objPHPExcel->getActiveSheet()->mergeCells('A47:H51');
$objPHPExcel->getActiveSheet()->setCellValue('A47', 'Протокол технической экспертизы № 7416 от 19.11.2022 г.
Испытательная лаборатория  ТОО «Евро-Тест»
РК, Алматинская область, Карасайский район,Жамбылский, село Улан,ул.
Жастар, 20А  Фактический:РК, город Алматы, Медеуский район, улица
Крымская дом 50'); 
$objPHPExcel->getActiveSheet()->getStyle('A47:H51')->applyFromArray($styleBorder);

$j=52;
$objPHPExcel->getActiveSheet()->setCellValue('A'.$j, '5.1-5.2');
$objPHPExcel->getActiveSheet()->getStyle('A'.$j)->applyFromArray($styleBorderBottom);
$objPHPExcel->getActiveSheet()->getStyle('B'.$j)->applyFromArray($styleBorderBottom);
$objPHPExcel->getActiveSheet()->getStyle('C'.$j)->applyFromArray($styleBorderBottom);
$objPHPExcel->getActiveSheet()->mergeCells('D'.$j.':X52');
$objPHPExcel->getActiveSheet()->getStyle('D'.$j.':X52')->applyFromArray($styleBorder);

$fields_ = Array(); 						$f=53;
$fields_[$f] = 'шасси';						$f++;
$fields_[$f] = 'телефон, факс';				$f++;
$fields_[$f] = 'адрес электронный';			$f++;
$fields_[$f] = 'пассажировмест. М2, М3';	$f++;
$fields_[$f] = 'общий объем баг. Отдел.';	$f++;
$fields_[$f] = 'кол-во мест для сидения';	$f++;
$fields_[$f] = 'Рамма (для кат. L)';		$f++;
$fields_[$f] = 'кол-во осей/колес кат. О';	$f++;
$fields_[$f] = 'опис. Гибрид. т/с';			$f++;
$fields_[$f] = 'электродиг. Электро';		$f++;
$fields_[$f] = 'раб. Напряж., В';			$f++;
$fields_[$f] = 'макс. 30-мин. Мощ. Квт';	$f++;
$fields_[$f] = 'устр. Накоп. Энергии';		$f++;
$fields_[$f] = 'электромашина';				$f++;
$fields_[$f] = 'раб. Напряж., В';			$f++;
$fields_[$f] = 'макс. 30-мин. Мощ. Квт';	$f++;
$fields_[$f] = 'сцепление';					$f++;
$fields_[$f] = 'габаритные размеры мм';		$f++;
$fields_[$f] = 'Тормозные системы (тип)';	$f++;

$value_ = Array();															$v=53;
$value_[$v] = stripslashes($r["chassis"]);									$v++;
$value_[$v] = stripslashes($r["user_phone"]).($r["user_fax"]!='' ? ', '.stripslashes($r["user_fax"]) : '');			$v++;
$value_[$v] = stripslashes($r["user_mail"]);								$v++;
$value_[$v] = stripslashes($r["count_pass"]);								$v++;
$value_[$v] = stripslashes($r["bagajnik"]);									$v++;
$value_[$v] = stripslashes($r["count_place_m2"]);							$v++;
$value_[$v] = stripslashes($r["rama"]);										$v++;
$value_[$v] = stripslashes($r["count_koles"]);								$v++;
$value_[$v] = stripslashes($r["gibrid"]);									$v++;
$value_[$v] = stripslashes($r["elektro"]);									$v++;
$value_[$v] = stripslashes($r["rab_napr"]);									$v++;
$value_[$v] = stripslashes($r["max_30mosh"]);								$v++;
$value_[$v] = stripslashes($r["nakop_energ"]);								$v++;
$value_[$v] = stripslashes($r["elek_marka"]);								$v++;
$value_[$v] = stripslashes($r["rab_napr2"]);								$v++;
$value_[$v] = stripslashes($r["max_30mosh2"]);								$v++;
$value_[$v] = stripslashes($r["ceplenie"]);									$v++;
$value_[$v] = stripslashes($r["dlina"]).($r["shirina"]!='' ? ', '.stripslashes($r["shirina"]) : '').($r["vysota"]!='' ? ', '.stripslashes($r["vysota"]) : '');		$v++;
$value_[$v] = stripslashes($r["tormoz"]);									$v++;

$stolbec__ = 'H';
$i=53;
foreach($fields_ as $k=>$v)
{	
	$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->applyFromArray($styleBorderBottom);
	$objPHPExcel->getActiveSheet()->getStyle('B'.$i)->applyFromArray($styleBorderBottom);
	$objPHPExcel->getActiveSheet()->getStyle('C'.$i)->applyFromArray($styleBorderBottom);
	$objPHPExcel->getActiveSheet()->setCellValue('A'.$i, str_replace('\n', '', $fields_[$i])); 	
		
	$objPHPExcel->getActiveSheet()->mergeCells('D'.$i.':'.$stolbec__.$i);
	$objPHPExcel->getActiveSheet()->getStyle('D'.$i.':'.$stolbec__.$i)->applyFromArray($styleBorder);
	$objPHPExcel->getActiveSheet()->setCellValue('D'.$i, str_replace('\n', '', @$value_[$i])); 	
		
	$i++;
}

$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

$page->setTitle('Заявка '.$r["id"]);						

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save($_SERVER["DOCUMENT_ROOT"]."/upload/order/".$name_file_excel);								
?>