<?
header('Content-Type: text/html; charset=utf-8');

include_once($_SERVER["DOCUMENT_ROOT"].'/libs/mysql.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/libs/api.php');
	
function getExtension($filename) 
{
    //return end(explode(".", $filename));	
	$temp = explode(".", $filename);
	return end($temp);
}

function jsOnResponseCab($obj)
{  
	echo'
	<script type="text/javascript"> 
		window.parent.onResponseCab("'.$obj.'"); 
 	</script>
	';  
}

function translit( $string ) 
{
  $converter = array(
    'а' => 'a', 'б' => 'b', 'в' => 'v',
    'г' => 'g', 'д' => 'd', 'е' => 'e',
    'ё' => 'e', 'ж' => 'zh', 'з' => 'z',
    'и' => 'i', 'й' => 'y', 'к' => 'k',
    'л' => 'l', 'м' => 'm', 'н' => 'n',
    'о' => 'o', 'п' => 'p', 'р' => 'r',
    'с' => 's', 'т' => 't', 'у' => 'u',
    'ф' => 'f', 'х' => 'h', 'ц' => 'c',
    'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sch',
    'ь' => "", 'ы' => 'y', 'ъ' => "",
    'э' => 'e', 'ю' => 'yu', 'я' => 'ya',
    ' ' => '-', 'ә' => 'a', 'і' => 'i',
    'ң' => 'n', 'ғ' => 'g', 'ү' => 'u',
    'ұ' => 'u', 'қ' => 'k', 'ө' => 'o',
    'һ' => 'kh',

    'А' => 'A', 'Б' => 'B', 'В' => 'V',
    'Г' => 'G', 'Д' => 'D', 'Е' => 'E',
    'Ё' => 'E', 'Ж' => 'Zh', 'З' => 'Z',
    'И' => 'I', 'Й' => 'Y', 'К' => 'K',
    'Л' => 'L', 'М' => 'M', 'Н' => 'N',
    'О' => 'O', 'П' => 'P', 'Р' => 'R',
    'С' => 'S', 'Т' => 'T', 'У' => 'U',
    'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
    'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sch',
    'Ь' => "", 'Ы' => 'Y', 'Ъ' => "",
    'Э' => 'E', 'Ю' => 'Yu', 'Я' => 'Ya',
    ' ' => '-', 'Ә' => 'A', 'І' => 'I',
    'Ң' => 'N', 'Ғ' => 'G', 'Ү' => 'U',
    'Ұ' => 'U', 'Қ' => 'K', 'Ө' => 'O',
    'Һ' => 'KH',
  );
  $string = str_replace( ' ', '-', $string );
  $string = preg_replace( '/[^a-zа-яё0-9\-]+/iu', '', $string );

  return strtolower( strtr( $string, $converter ) );
}

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

$format = explode("|", 'jpg|png|pdf|jpeg|JPG|PNG|PDF|JPEG');

$count = intval($_POST["count"]);
$id = intval($_POST["order"]);

if (
	($count >= 1 && $count <= 12) &&
	($id > 0)
) {
	if (!empty($_FILES['img'.$count]['tmp_name']))
	{  
		if ($_FILES['img'.$count]['size']<=5000000)
		{
			if (in_array(getExtension($_FILES['img'.$count]['name']),$format))
			{
				if ($api->Managers->check_auth() == true && $api->Managers->man_block == 3)
				{		
					$sql_wh = " AND ( ( (`status`=1 OR `status`=2) AND (`id_exam`='".$api->Managers->man_id."' OR `id_exam`=0 OR `id_exam` IS NULL) ) OR ( (`status`=3 OR `status`=4) AND `id_exam`='".$api->Managers->man_id."') )";
					
					$s = mysql_query("SELECT * FROM `i_order` WHERE `id`='".$id."'".$sql_wh." LIMIT 1");
					if (mysql_num_rows($s) > 0)
					{
						$r=mysql_fetch_array($s);
					
						$dir = $_SERVER['DOCUMENT_ROOT'].'/upload/foto/';							
						$ext = getExtension($_FILES['img'.$count]['name']);
						//$name = str_replace('.'.$ext, '', $_FILES['img'.$count]['name']).'_'.date("YmdHis").'.'.$ext; // начало как реальное название файла
						$name = $id.'_'.$count.'_'.translit($foto_name[$count]).'_'.date("YmdHis").'.'.$ext;
						$file = $dir.$name;
						$success = move_uploaded_file($_FILES['img'.$count]['tmp_name'], $file);  						

						if (!$success) { $erorr='Не возможно загрузить файл!'; }
						else
						{			
							$sF = mysql_query("SELECT `id` FROM `i_foto` WHERE `id_order`='".$id."' LIMIT 1");
							if (mysql_num_rows($sF) > 0)
							{
								$rF=mysql_fetch_array($sF);
								$id_foto = intval($rF["id"]);
							
								$sql = "UPDATE `i_foto` SET `foto_".$count."`='".$name."' WHERE `id`='".$id_foto."'";
								$update = mysql_query($sql);
							}
							else
							{
								$sql_insert = "INSERT INTO `i_foto` (`id_order`, `foto_".$count."`) VALUES ('".$id."', '".$name."')";
								$insert = mysql_query($sql_insert);	
								
								$sql_update = "UPDATE `i_order` SET `id_exam`='".$api->Managers->man_id."' WHERE `id`='".$id."'";
								$update_order = mysql_query($sql_update);
							}
						}
					}
				}
				else $erorr = 'Вам нужно авторизоваться';
			}
			else $erorr = 'Не верный формат файла';
		}
		else $erorr = "Файл превышает размер 5MB";
	}
	else $erorr = "Ошибка";
}
else $erorr = "Ошибка загрузки";

jsOnResponseCab("{'success':'".@$success."','name':'".@$name."','img':'".$count."','erorr':'".@$erorr."'}");
?>