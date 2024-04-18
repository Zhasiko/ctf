<?
// error_reporting(E_ALL);
// ini_set('display_errors', TRUE);
// ini_set('display_startup_errors', TRUE);
$logs = new Logs;
class Logs
{
	// возврашает массив в котором есть список столбцов таблицы
	function readColsOfTable($tablename)
	{		
		$res=mysql_query("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '".$tablename."' ORDER BY ORDINAL_POSITION");
		if (mysql_num_rows($res) > 0) {
			$cols = mysql_fetch_array($res);
			return $cols;
		} else{
			return FALSE;
		}
	}

	// возврашает строку таблицы в формате JSON
	function rowToJSON($table, $id)
	{		
		$cols = $this->readColsOfTable($table);
		$qry = mysql_query("SELECT * FROM `".$table."` WHERE `id`='".$id."'");
		if (mysql_num_rows($qry) > 0) {
			$json_array = JSON_encode(array_map('htmlspecialchars', mysql_fetch_assoc($qry)), JSON_UNESCAPED_UNICODE);
			return str_replace("&amp;", "&", $json_array);
		}else{
			return FALSE;
		}

	}

	//вставка записи в таблицу i_logs
	function insertToLogs($table, $id, $user_id)
	{		
		$json_arr1 = $this->rowToJSON($table, $id, $user_id);
		$del_date = date("Y-m-d H:i:s");
		$insert_log1 = mysql_query("INSERT INTO `i_logs` (`id_user`, `table`, `create_date`, `json_obj`) VALUES ('".$user_id."', '".$table."', '".$del_date."', '".$json_arr1."')");
		return mysql_insert_id();
	}
	
	//ПРЕОБРАЗОВАНИЕ JSON ОБЪЕКТА НА МАССИВ
	function jsonToArray($obj)
	{
		$arr = json_decode($obj, true);
		return $arr;
	}
		
	//восстановление удаленных таблиц
	function restoreDoc($id)
	{		
		$rD = mysql_query("SELECT * FROM `i_logs` WHERE `id`='".$id."' LIMIT 1");
		if (mysql_num_rows($rD) > 0)
		{
			$sD=mysql_fetch_array($rD);
			
			$json_obj =  $sD['json_obj'];
			$arr = json_decode($json_obj, true);
			$cols = '(';
			$vals = '(';
			$arr2 = json_decode($json_obj, true);
			end($arr2);
			$last_key = key($arr2);

			foreach ($arr as $k => $v) 
			{					
				if ($k != $last_key)
				{
					$cols .= "`".$k."`, ";
					
					if ($k == 'id_man' || $k == 'id_exam' || $k == 'id_broker' || $k == 'id_temp')
						$vals .= "'".intval($v)."', ";
					else
						$vals .= "'".$v."', ";
				}
				else
				{
					$cols .= "`".$k."`)";
					$vals .= "'".$v."')";
				}
			}

			$insert = mysql_query("INSERT INTO `".$sD['table']."` ".$cols." VALUES ".$vals);				
			$id_order = mysql_insert_id();
			
			if ($insert)
				$delete = mysql_query("DELETE FROM `i_logs` WHERE `id`='".$id."'");
			
			if ($delete)			
				return $id_order;			

		} else{
			return false;
		}
	}	
}
?>
