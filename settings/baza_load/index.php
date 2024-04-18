<?
//error_reporting(E_ALL);
//ini_set('display_errors', TRUE);
//ini_set('display_startup_errors', TRUE);
ini_set("max_execution_time", "900");
set_time_limit(900);
ini_set("memory_limit", "512M");

$lang="ru";
$title='Загрузить файл базы';

require($_SERVER["DOCUMENT_ROOT"]."/libs/header.php");

?>
<div class="card">
	<div class="card-body" style="position:relative">				
		<script type="text/javascript">

			jQuery(document).ready(function(){
				jQuery(window).scroll(function() {
					var top = jQuery(document).scrollTop();
					if (top > 100) { jQuery('.fixed_scroll').addClass("active_scroll"); }
					else		   { jQuery('.fixed_scroll').removeClass("active_scroll"); }
				});
			});

		</script>
		<div class="fixed_scroll">
			<div class="container_add">
				<div class="table-responsive_up_scroll"><div class="up_scroll"></div></div>
			</div>
		</div>
		<div class="table-responsive">
			<div id="basic-datatables_wrapper" class="dataTables_wrapper dt-bootstrap4">
<?

if ($api->Managers->check_auth() == true)
{	
	if (
		$api->Managers->man_block == 1 || 
		$api->Managers->man_block == 2 || $api->Managers->man_block == 5
	)
	{

		function getExtension($filename) 
		{
			$temp = explode(".", $filename);
			return end($temp);
		}

		if (@$_POST['hidden']=='hidden')
		{		
			if (!empty($_FILES['file_csv']['tmp_name']))
			{
				$format = explode("|", 'csv|CSV');
				if (in_array(getExtension($_FILES['file_csv']['name']),$format))
				{
					$ext = getExtension($_FILES['file_csv']['name']);
					$dateUpdate = str_replace('.'.$ext, '', $_FILES['file_csv']['name']);
					$nameFile = date("Ymd").'_'.$dateUpdate.".csv";			
					$dirCSV = $_SERVER['DOCUMENT_ROOT']."/upload/baza/".$nameFile;

					$upload = move_uploaded_file($_FILES['file_csv']['tmp_name'], $dirCSV);
					$file = fopen($dirCSV, "r");
					
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
					
					$est = ''; $e = 1;
					$add = ''; $a = 1;
					
					while($data=fgets($file,1000000))
					{
						$data = explode(";", $data);
						
						if ($data[0]!='' && $data[1]!='' && $data[2]!='' && $data[3]!='' && $data[4]!='')
						{						
							$k=0;
							$fields = Array(); $sql_field = ""; $sql_value = "";
							foreach($columns as $k=>$v)
							{
								$value = trim(str_replace('\r\n', '', ($api->Strings->pr(iconv('windows-1251', 'UTF-8', @$data[$k])))));																
								$fields[$v] = $value;
								$k++;

								$sql_field .= ($sql_field!='' ? ", " : "")."`".$v."`";
								$sql_value .= ($sql_value!='' ? ", " : "")."".($fields[$v]=='' ? "NULL" : "'".addslashes($fields[$v])."'");
							}		

							//echo '<pre>'; print_r($fields); echo '</pre>';

							$s=mysql_query("SELECT `id` FROM `i_baza` WHERE `car_type`='".$fields["car_type"]."' AND `mark`='".$fields["mark"]."' AND `com_name`='".$fields["com_name"]."' AND `year`='".$fields["year"]."' AND `volume`='".$fields["volume"]."' LIMIT 1");
							if (mysql_num_rows($s) == 1)
							{
								$r=mysql_fetch_array($s);

								$link = ' style="cursor:pointer;" onclick="window.open(\'https://'.$_SERVER["HTTP_HOST"].'/settings/baza/add.php?edit='.$r["id"].'\', \'_blank\')"';

								$est .= '
								<tr role="row">
									<td'.$link.' nowrap="nowrap">'.$e.'</td>
									<td'.$link.' nowrap="nowrap">'.stripslashes($fields["car_type"]).'</td>
									<td'.$link.' nowrap="nowrap">'.stripslashes($fields["mark"]).'</td>
									<td'.$link.' nowrap="nowrap">'.$api->Strings->pr_plus(htmlspecialchars_decode(stripslashes($fields["com_name"]))).'</td>
									<td'.$link.' nowrap="nowrap">'.stripslashes($fields["year"]).'</td>
									<td'.$link.' nowrap="nowrap">'.stripslashes($fields["volume"]).'</td>
								</tr>';

								$e++;
							}
							else
							{				
								$sql_insert = "INSERT INTO `i_baza` (".$sql_field.") VALUES (".$sql_value.")";
								$insert = mysql_query($sql_insert);				

								if ($insert)					
								{
									$add .= '
									<tr role="row">
										<td nowrap="nowrap">'.$a.'</td>
										<td nowrap="nowrap">'.stripslashes($fields["car_type"]).'</td>
										<td nowrap="nowrap">'.stripslashes($fields["mark"]).'</td>
										<td nowrap="nowrap">'.$api->Strings->pr_plus(htmlspecialchars_decode(stripslashes($fields["com_name"]))).'</td>
										<td nowrap="nowrap">'.stripslashes($fields["year"]).'</td>
										<td nowrap="nowrap">'.stripslashes($fields["volume"]).'</td>
									</tr>';

									$a++;
								}
							}	
						}
					}																							
					
					?>
					<table id="basic-datatables" class="display table table-striped table-hover dataTable">
						<thead>
							<tr>			
								<th>№</th>
								<th>Тип автомобиля</th>
								<th>Марки</th>
								<th>Ком наименование</th>
								<th>Год</th>
								<th>Объём</th>
							</tr>
						</thead>
						<tbody>
					<?
					
					if ($est != '')
						echo '
						<tr>
							<td nowrap="nowrap" colspan="6" style="text-align:center"><h3 style="color:#f00;">Уже есть в базе:</h3></td>
						</tr>'.$est;
					
					if ($add != '')
						echo 
						($est != '' ? '<td colspan="6">&nbsp;</td>' : '').'
						<tr>
							<td nowrap="nowrap" colspan="6" style="text-align:center"><h3 style="color:#53b374;">Новые записи:</h3></td>
						</tr>'.$add;
					
					?>
						</tbody>
					</table>
					<h2 style="margin:20px 0 0; color:#53b374;">Загрузка полностью завершена!</h2>		
					<?							
				}
				else
					echo '<h2 style="color:#f00">Неверный формат файла! Обновите страницу!</h2>';
			}		
			else
				echo '<h2 style="color:#f00">Не загрузили файл! Обновите страницу.</h2>';
		}
		else
		{	
			?>
			<div style="margin:0 0 20px; padding:0 0 20px; border-bottom:1px solid #ccc">
				<form method="post" action="" enctype="multipart/form-data">
					<input type="file" name="file_csv" class="btn btn-info" />
					<input type="submit" class="btn btn-success" value="Загрузить" />
					<input type="hidden" name="hidden" value="hidden" />
				</form>
			</div>          
			<?
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
			
			$fields = Array(); $kk=1;
			foreach($columns as $k=>$v)
			{
				$fields[$kk] = $name_ru[$v];
				$kk++;
			}		

			echo '
			<h3 style="color:#53b374;">Порядок полей в .CSV файле:</h3>
			<pre>'; print_r($fields); echo '</pre>';
		}
	}
	else
		require($_SERVER["DOCUMENT_ROOT"]."/text_noAcces.php");
}
else
	require($_SERVER["DOCUMENT_ROOT"]."/text_noAuth.php");
				
?>
			</div>
		</div>
	</div>
</div>				
<?

require($_SERVER["DOCUMENT_ROOT"]."/libs/footer.php");?>