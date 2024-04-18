<?
if (isset($_GET["name"]) && $_GET["name"] != '')
{
	include_once($_SERVER['DOCUMENT_ROOT']."/libs/mysql.php");
	include_once($_SERVER['DOCUMENT_ROOT']."/libs/api.php");
	
	$hash = trim($api->Strings->pr($_GET["name"]));
	$sql_query = "SELECT `pdf_file_client` FROM `i_order` WHERE `hash`='".$hash."'";
	$s = mysql_query($sql_query);
	if (mysql_num_rows($s) > 0)
	{			
		$r = mysql_fetch_array($s);
		$filename = $r["pdf_file_client"];
		
		$dirFile = $_SERVER['DOCUMENT_ROOT'].'/upload/client_pdf/'.$filename;
		if (is_file($dirFile)) 
		{
			header('Content-Type: application/pdf');
			header('Content-Disposition: attachment; filename="'.$filename.'"');
			readfile($_SERVER['DOCUMENT_ROOT'].'/upload/client_pdf/'.$filename);
		}
		else header("Location:/old_maket.php");
	}
	else header("Location:/old_maket.php");
}
else header("Location:/404.php");
?>