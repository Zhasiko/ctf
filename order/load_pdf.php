<?
include_once($_SERVER['DOCUMENT_ROOT']."/libs/mysql.php");
include_once($_SERVER['DOCUMENT_ROOT']."/libs/api.php");
if ($api->Managers->check_auth() == true)
{	
	if (intval($_GET["load"]) > 0)
	{
		$sql_query = "SELECT `pdf_file` FROM `i_order` WHERE `id`='".intval($_GET["load"])."'";
		$s = mysql_query($sql_query);
		if (mysql_num_rows($s) > 0)
		{			
			$r = mysql_fetch_array($s);
			
			$filename = $r["pdf_file"];
			
			header('Content-Type: application/pdf');
			header('Content-Disposition: attachment; filename="'.$filename.'"');
			readfile($_SERVER['DOCUMENT_ROOT'].'/upload/order/'.$filename);

		}
		else { header("Location:/order/"); }
	}
	else if (intval($_GET["file"]) >= 0 && (intval($_GET["type"]) >= 1 && intval($_GET["type"]) <= 4))
	{
		$sql_query = "SELECT * FROM `i_order` WHERE `id`='".intval($_GET["file"])."'";
		$s = mysql_query($sql_query);
		if (mysql_num_rows($s) > 0)
		{			
			$r = mysql_fetch_array($s);
						
			if (intval($_GET["type"]) == '1')			$type_file = 'protocol';
			else if (intval($_GET["type"]) == '2')		$type_file = 'reshenie';
			else if (intval($_GET["type"]) == '3')		$type_file = 'zayavka';
			else if (intval($_GET["type"]) == '4')		$type_file = 'dogovor';

			$nn_f = $r["user_name"].'-'.$r["mark"].'-'.$r["vin"].'-'.$type_file.'-'.$r["id"];
			$name_file_pdf = $api->Strings->translit($nn_f).'.pdf';
			
			header('Content-Type: application/pdf');
			header('Content-Disposition: attachment; filename="'.$name_file_pdf.'"');
			readfile($_SERVER['DOCUMENT_ROOT'].'/upload/order/'.$type_file.'/'.$name_file_pdf);

		}
		else { header("Location:/order/"); }
	}
	else { header("Location:/order/"); }
}
else { header("Location:/"); }
?>