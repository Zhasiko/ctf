<?
include_once($_SERVER['DOCUMENT_ROOT']."/libs/mysql.php");
include_once($_SERVER['DOCUMENT_ROOT']."/libs/api.php");
if ($api->Managers->check_auth() == true)
{	
	if (intval($_GET["load"]) > 0)
	{
		$sql_query = "SELECT `excel_file` FROM `i_order` WHERE `id`='".intval($_GET["load"])."'";
		$s = mysql_query($sql_query);
		if (mysql_num_rows($s) > 0)
		{			
			$r = mysql_fetch_array($s);
			
			$filename = $r["excel_file"];
			
			header('Content-Type: application/excel');
			header('Content-Disposition: attachment; filename="'.$filename.'"');
			readfile($_SERVER['DOCUMENT_ROOT'].'/upload/order/'.$filename);

		}
		else { header("Location:/order/"); }
	}
	else if (intval($_GET["exam"]) == 1)
	{					
		$name_file_excel = 'orders_'.date("YmdHis").'.xlsx';	
		$dirFile = $_SERVER['DOCUMENT_ROOT'].'/upload/excel_exam/'.$name_file_excel;
		if (is_file($dirFile)) 
		{
			header('Content-Type: application/excel');
			header('Content-Disposition: attachment; filename="'.$name_file_excel.'"');
			readfile($_SERVER['DOCUMENT_ROOT'].'/upload/excel_exam/'.$name_file_excel);		
		}
		else { header("Location:/order/"); }
	}
	else { header("Location:/order/"); }
}
else { header("Location:/"); }
?>