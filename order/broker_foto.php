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
$format = explode("|", 'jpg|png|pdf|jpeg|JPG|PNG|PDF|JPEG');

$count = intval($_POST["count"]);
$id = intval($_POST["order"]);

if ($count >= 1 && $count <= 10) 
{
	if (!empty($_FILES['img'.$count]['tmp_name']))
	{  
		if ($_FILES['img'.$count]['size']<=5000000)
		{
			if (in_array(getExtension($_FILES['img'.$count]['name']),$format))
			{
				if (
					($api->Managers->check_auth() == true) && 
					(
						($api->Managers->man_block == 4) ||
						($api->Managers->man_block == 1 || $api->Managers->man_block == 2 || $api->Managers->man_block == 5)
					)
				)
				{												
					$dir = $_SERVER['DOCUMENT_ROOT'].'/upload/foto_broker/';							
					$ext = getExtension($_FILES['img'.$count]['name']);
					//$name = str_replace('.'.$ext, '', $_FILES['img'.$count]['name']).'_'.date("YmdHis").'.'.$ext; // начало как реальное название файла
					$name = date("YmdHis").'_'.$count.'_'.translit($foto_name[$count]).'.'.$ext;
					$file = $dir.$name;
					$success = move_uploaded_file($_FILES['img'.$count]['tmp_name'], $file);  						

					if (!$success) { $erorr='Не возможно загрузить файл!'; }
					else
					{			
						
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