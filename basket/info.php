<?
$lang="ru";
$title="Заявка";
$keywords="";
$description="";

if (
	isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') &&
	isset($_POST['do']) &&
	isset($_POST['x']) && ($_POST['x']=='secure')
	)
{

	include_once($_SERVER['DOCUMENT_ROOT']."/libs/mysql.php");
	include_once($_SERVER['DOCUMENT_ROOT']."/libs/api.php");

	if (
		$api->Managers->check_auth() == true &&
		(
			$api->Managers->man_block == 1 || // админ 
			$api->Managers->man_block == 5 // менеджеры
		)
	)
	{		
		if (
			($_POST['do'] == 'delete') &&
			(isset($_POST['edit']) && intval($_POST['edit']) != 0)
		)
		{			
			if ($api->Managers->man_block == 1 || $api->Managers->man_block == 5)
			{
				$id = intval($_POST["edit"]);
												
				include_once($_SERVER["DOCUMENT_ROOT"].'/basket/logs.php');
					
				$id_order = $logs->restoreDoc($id);

				if ($id_order > 0)
				{					
					echo '
					<script type="text/javascript">
						jQuery("#protocolDel").html("<span style=\"color:#53b374\">Вы успешно восстановили заявку</span>");
						setTimeout(function() { self.location = "/order/more.php?edit='.$id_order.'"; }, 1000);
					</script>';
				}
				else
					echo '
					<script type="text/javascript">
						jQuery("#protocolDel").html("<span style=\"color:#f00\">Ошибка удаления!</span>");								
					</script>';
									
			}
		}
				
	}

	exit;
}

require($_SERVER["DOCUMENT_ROOT"]."/libs/header.php");

if ($api->Managers->check_auth() == true)
{
	if (
		(
			$api->Managers->man_block == 1 || // админ 
			$api->Managers->man_block == 5 // менеджеры			
		) &&
		(isset($_GET["edit"]) && intval($_GET["edit"]) != 0)
	)
	{				
		$id = intval($_GET["edit"]);
		$s = mysql_query("SELECT * FROM `i_logs` WHERE `id`='".$id."' LIMIT 1");
		if (mysql_num_rows($s) > 0)
		{
			$r=mysql_fetch_array($s);
			
			$users = Array();
			$sU = mysql_query("SELECT * FROM `i_manager_users`");
			if (mysql_num_rows($sU) > 0)
			{	
				while($rU=mysql_fetch_array($sU))
				{
					$users[$rU["id"]]["name"] = stripslashes($rU["name"]);	
				}
			}
			
			$date_l = $api->Strings->date($lang,$r["create_date"],'sql','datetime');
			$man_name_l = $users[$r["id_user"]]["name"];
			
			$json_obj = json_decode($r['json_obj'], true);
						
			$date = $api->Strings->date($lang,$json_obj["create_date"],'sql','datetime');

			$status = '';
			switch (intval($json_obj["status"])) {
				case 0:
					$status = 'на одобрении'; $class_st = 'appr';
					break;
				case 1:
					$status = 'новая'; $class_st = 'new';
					break;
				case 2:
					$status = 'в работе'; $class_st = 'work';
					break;
				case 3:
					$status = 'готова'; $class_st = 'done';
					break;
				case 4:
					$status = 'изменено досмотрщиком'; $class_st = 'change';
					break;
			}						

			$man_name = $users[$json_obj["id_man"]]["name"];
			$exam_name = $users[$json_obj["id_exam"]]["name"];
			$broker_name = $users[$json_obj["id_broker"]]["name"];						
			?>
			<div class="card">  
				<div class="card-header">
					<div class="card-title" style="display:inline-block;">Информация по удалению:</div>					
					<div class="but_del">
						<button type="buton" class="btn btn-info del_action" onclick="deleteZ();">Восстановить</button>
						<span class="loading" id="load_del"><img src="/library/img/load.gif" /></span>
						<div id="protocolDel"></div>
					</div>			
					<script type="text/javascript">
						
						function deleteZ()
						{
							var err_key = 0;
							var focused = 0;

							if (confirm("Вы действительно хотите восстановить запись?"))
							{
								jQuery.ajax(
								{
									url: "info.php",
									data: "do=delete&edit=<?=intval($_GET["edit"])?>&x=secure",
									type: "POST",
									dataType : "html",
									cache: false,

									beforeSend: function()		{ jQuery("#protocolDel").html(""); jQuery(".del_action").hide(); jQuery("#load_del").show(); },
									success:  function(data)	{ jQuery("#protocolDel").html(data); jQuery("#load_del").hide(); },
									error: function()			{ alert("Невозможно связаться с сервером"); jQuery(".del_action").show(); jQuery("#load_del").show(); }
								});
							}
						}
						
					</script>
				</div>
				<div class="card-body more_mob">
					<div class="row">
						<label class="col-lg-4 col-md-3 col-sm-4 text-right">Дата удаления:</label>
						<label class="col-lg-8 col-md-9 col-sm-8"><strong><?=$date_l?></strong></label>            
					</div>
					<div class="row mt-2">
						<label class="col-lg-4 col-md-3 col-sm-4 text-right">Менеджер:</label>
						<label class="col-lg-8 col-md-9 col-sm-8"><strong><?=$man_name_l?></strong></label>            
					</div>
				</div>					
				<div class="card-header" style="border-top:1px solid #ebecec">
					<div class="card-title" style="display:inline-block;">Информация по заявке:</div>
				</div>
				<div class="card-body more_mob">
					<div class="row">
						<label class="col-lg-4 col-md-3 col-sm-4 text-right">Дата создания:</label>
						<label class="col-lg-8 col-md-9 col-sm-8"><strong><?=$date?></strong></label>            
					</div>
					<div class="row mt-2">
						<label class="col-lg-4 col-md-3 col-sm-4 text-right">Статус:</label>
						<label class="col-lg-8 col-md-9 col-sm-8"><strong class="stat <?=$class_st?>"><?=$status?></strong></label>            
					</div>
					<div class="row mt-2">
						<label class="col-lg-4 col-md-3 col-sm-4 text-right">Брокер:</label>
						<label class="col-lg-8 col-md-9 col-sm-8"><strong><?=$broker_name?></strong></label>            
					</div>
					<? if ($man_name != '') { ?>
					<div class="row mt-2">
						<label class="col-lg-4 col-md-3 col-sm-4 text-right">Менеджер:</label>
						<label class="col-lg-8 col-md-9 col-sm-8"><strong><?=$man_name?></strong></label>            
					</div>
					<? } ?>
					<? if ($exam_name != '') { ?>
					<div class="row mt-2">
						<label class="col-lg-4 col-md-3 col-sm-4 text-right">Досмотрщик:</label>
						<label class="col-lg-8 col-md-9 col-sm-8"><strong><?=$exam_name?></strong></label>            
					</div>					
					<? } ?>
					<div class="row mt-2">
						<label class="col-lg-4 col-md-3 col-sm-4 text-right">ФИО:</label>
						<label class="col-lg-8 col-md-9 col-sm-8"><strong><?=$json_obj["user_name"]?></strong></label>            
					</div>		
					<div class="row mt-2">
						<label class="col-lg-4 col-md-3 col-sm-4 text-right">Телефон:</label>
						<label class="col-lg-8 col-md-9 col-sm-8"><strong><?=$json_obj["user_phone"]?></strong></label>            
					</div>
					<? if ($json_obj["user_mail"] != '') { ?>
					<div class="row mt-2">
						<label class="col-lg-4 col-md-3 col-sm-4 text-right">E-mail:</label>
						<label class="col-lg-8 col-md-9 col-sm-8"><strong><?=$json_obj["user_mail"]?></strong></label>            
					</div>
					<? } ?>
					<div class="row mt-2">
						<label class="col-lg-4 col-md-3 col-sm-4 text-right">Машина:</label>
						<label class="col-lg-8 col-md-9 col-sm-8"><strong><?=$json_obj["car_type"]?>: <?=$json_obj["mark"]?>, <?=$json_obj["com_name"]?>, <?=$json_obj["year"]?>, <?=$json_obj["volume"]?></strong></label>            
					</div>
					<div class="row mt-2">
						<label class="col-lg-4 col-md-3 col-sm-4 text-right">VIN:</label>
						<label class="col-lg-8 col-md-9 col-sm-8"><strong><?=$json_obj["vin"]?></strong></label>            
					</div>
				</div>
			</div>	
			<?	
		}
		else
			require($_SERVER["DOCUMENT_ROOT"]."/text_noAcces.php");			
	}
	else
		require($_SERVER["DOCUMENT_ROOT"]."/text_noAcces.php");
}
else
	require($_SERVER["DOCUMENT_ROOT"]."/text_noAuth.php");
require($_SERVER["DOCUMENT_ROOT"]."/libs/footer.php");?>
