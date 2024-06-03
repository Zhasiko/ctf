<?
header('Content-Type: text/html; charset=utf-8');
$lang = 'ru';

if (
	isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') &&
	isset($_POST['x']) && ($_POST['x']=='secure')
	)
{
    include_once($_SERVER['DOCUMENT_ROOT']."/libs/mysql.php");
    include_once($_SERVER['DOCUMENT_ROOT']."/libs/api.php");

    require_once '../get_encr_key.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && 
    isset($_POST['id']) && $_POST["id"] != '' &&
    isset($_POST['user_flag']) && $_POST["user_flag"] != '' &&
    isset($_POST['category']) && $_POST["category"] != '' 
    ) {

        $id = trim($_POST['id']);
        $user_id = $api->Managers->man_id;
        $userFlag = trim($_POST['user_flag']);
        $category = trim($_POST['category']);

        $correctFlag = "";


        $s=mysql_query("SELECT * FROM `i_order` WHERE `id`='".$id."' LIMIT 1");
        
        if (mysql_num_rows($s) > 0) {
            $r = mysql_fetch_array($s);
            $flag = decryptPassword($r["flag"], $encryption_key);            
            $points = $r["points"];
            $correctFlag = trim($flag);
        }
        
        if($correctFlag != ""){

            if ($userFlag === $correctFlag) {
                
                if(($id != '') && ($user_id != '') && (mysql_num_rows(mysql_query("SELECT `id` FROM `i_solved` WHERE `task_id`='".$id."' AND `user_id`='".$user_id."' LIMIT 1")) == 0)){

                        $sql_insert = "INSERT INTO `i_solved` (`task_id`, `user_id`, `solved_date`, `category`) VALUES ('".$id."', '".$user_id."', '".date("Y-m-d H:i:s")."', '".$category."')";

                        $insert = mysql_query($sql_insert);
            
                        $sql_update = "UPDATE `i_manager_users` SET `points`= `points` + '".$points."', `task_amount`= `task_amount` + 1 WHERE `id`='".$user_id."'";
                        $update = mysql_query($sql_update);
                }
                
                echo '
                    <script type="text/javascript">
                        jQuery("#protocol").html("<span style=\"color:#53b374\">Вы успешно решили задачу</span>");
                        
                    </script>';	

            } else {
               echo '
                    <script type="text/javascript">
                        jQuery("#protocol").html("<span style=\"color:red\">Неправильный флаг</span>");
                        
                    </script>';	
            }
        }
    }

}
?>