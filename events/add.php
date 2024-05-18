<?

require_once '../get_encr_key.php';


$lang="ru";
$title="Добавить событие";
if (isset($_GET["edit"]) && intval($_GET["edit"])!=0)
	$title="Редактировать событие";
$keywords="";
// $description="";
require($_SERVER["DOCUMENT_ROOT"]."/libs/header.php");

if ($api->Managers->check_auth() == true)
{
	if ($api->Managers->man_block == 1)
	{
		?>
		 <style>

			body {
				background-color: #21232c; /* Цвет фона */
			}
            .card {
                background-color: #1a2035; /* Цвет фона */
            }
            .card-body{
                color: white !important;
            }
			
			/* .basic-datatables{
				background-color: #21232c;
			} */
		</style>
		<div class="card">
            <div class="card-body">
				<?
                $active_value = 1;
                $event_name_value = '';
				$description_value = '';
				$date_value = '';
                $link_value = '';
				
				
                if (isset($_GET["edit"]) && intval($_GET["edit"]) != 0)
                {
					
					$sql_ = '';										
                    $s=mysql_query("SELECT * FROM `i_events` WHERE `id`='".intval($_GET["edit"])."'".$sql_." ORDER BY `id` ASC LIMIT 1");
                    if (mysql_num_rows($s) > 0)
                    {
						
                        $r=mysql_fetch_array($s);

                        $active_value = intval($r["active"]);
                        $event_name_value = stripslashes($r["name"]);
						$description_value = $r["description"];
						$date_value = $r["date"];
                        $link_value = $r["link"];
                        
						// $hashed_password = password_hash($password_value, PASSWORD_DEFAULT);
						
                    }
					// print_r($active_value); echo "<br>";
					// print_r($event_name_value); echo "<br>";
					// print_r($description_value); echo "<br>";
					// print_r($date_value); echo "<br>";
					// print_r($link_value); echo "<br>";
                }
				
                ?>

				<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
				<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
				<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js"></script>
				<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.min.css">
                <div class="form-group form-show-validation row">
                    <label for="active" class="col-lg-4 col-md-3 col-sm-4 mt-sm-2 text-right" style = "color: white !important;" >Статус <span class="required-label">*</span></label>
                    <div class="col-lg-4 col-md-9 col-sm-8">
                        <select class="form-control" id="active">
                        	<option value="1"<?=($active_value == 1 ? ' selected' : '')?>> Актуальный </option>
							<option value="0"<?=($active_value == 0 ? ' selected' : '')?>> Неактуальный </option>
						</select>
                	</div>
                   
            	</div>

				<div class="form-group form-show-validation row">
                    <label for="name" class="col-lg-4 col-md-3 col-sm-4 mt-sm-2 text-right" style = "color: white !important;">Название <span class="required-label">*</span></label>
                    <div class="col-lg-4 col-md-9 col-sm-8">
                        <input type="text" class="form-control" id="name" value="<?=$event_name_value?>" />
                        <span class="control__help" id="error_name"></span>
                    </div>
                </div>


				<div class="form-group form-show-validation row">
                    <label for="description" class="col-lg-4 col-md-3 col-sm-4 mt-sm-2 text-right" style = "color: white !important;">Описание <span class="required-label">*</span></label>
                    <div class="col-lg-4 col-md-9 col-sm-8">
                        <input type="text" class="form-control" id="description" value="<?=$description_value?>" />
                        <span class="control__help" id="error_description"></span>
                    </div>
                </div>

				<div class="form-group form-show-validation row">
					<label for="date" class="col-lg-4 col-md-3 col-sm-4 mt-sm-2 text-right" style = "color: white !important;">Дата <span class="required-label">*</span></label>
					<div class="col-lg-4 col-md-9 col-sm-8">
						<input type="text" class="form-control" id="date" value="<?=$date_value?>" />
						<span class="control__help" id="error_date"></span>
					</div>
				</div>

				
                <div class="form-group form-show-validation row">
                    <label for="mail" class="col-lg-4 col-md-3 col-sm-4 mt-sm-2 text-right" style = "color: white !important;">Ссылка <span class="required-label">*</span></label>
                    <div class="col-lg-4 col-md-9 col-sm-8">
                        <input type="text" class="form-control" id="link" value="<?=$link_value?>"  />
                        <span class="control__help" id="error_link"></span>
                    </div>
                </div>


				
				
			</div>

            <div class="card-action t-right" id="action">				
            	<? if (isset($_GET["edit"]) && intval($_GET["edit"]) != 0) { ?>
                <button type="button" class="btn btn-danger action"  style="margin-right:50px" onclick="deleteEvent();">Удалить</button>
                <? } else { ?>
				<a class="btn btn-warning action" style="float:left" href="javascript:history.go(-1)">Вернуться назад</a>
				<? } ?>
                <button class="btn btn-success action" onclick="addEvent();"><?=(isset($_GET["edit"]) ? 'Сохранить' : 'Добавить')?></button>
                <div class="loading">
                    <img src="/library/img/load.gif" /> Ваш запрос обрабатывается...
                </div>
                <div id="protocol"></div>
            </div>

		</div>
		<script type="text/javascript">

			

			$('#date').datetimepicker({
				format: 'd-m-Y H:i',
				step: 30
			});

			

			function addEvent()
			{
				
				var err_key = 0;
				var focused = 0;

				jQuery(".calc__card input").css("border-color", "#c9cbcd");
				jQuery(".control__help").html('').hide();

				// if (jQuery("#cat").val() == '')
				// {
				// 	err_key = 1;
				// 	jQuery("#cat").css("border-color", "#f00");
				// 	jQuery("#error_cat").html('Не выбрана категория').css("display", "inline-block");
				// 	if (focused == 0) { jQuery("#cat").focus(); focused = 1; }
				// }

				if (jQuery("#name").val() == '')
				{
					err_key = 1;
					jQuery("#name").css("border-color", "#f00");
					jQuery("#error_name").html('Не заполнено поле Название').css("display", "inline-block");
					if (focused == 0) { jQuery("#name").focus(); focused = 1; }
				}
				if (jQuery("#description").val() == '')
				{
					err_key = 1;
					jQuery("#description").css("border-color", "#f00");
					jQuery("#error_description").html('Не заполнено поле Описание').css("display", "inline-block");
					if (focused == 0) { jQuery("#description").focus(); focused = 1; }
				}
				<?php /*?>var phone = jQuery("#login").val();
				phone = phone.replace(/_/g, "");<?php */?>
				if (jQuery("#link").val()=="" <?php /*?>|| phone.length != 14<?php */?>)
				{
					err_key = 1;
					jQuery("#error_link").html('Не заполнено поле Ссылка').css("display", "inline-block");
					jQuery("#link").css("border-color", "#f00");
					if (focused == 0) { jQuery("#link").focus(); focused = 1; }
				}

				if (jQuery("#date").val() == '')
				{
					err_key = 1;
					jQuery("#date").css("border-color", "#f00");
					jQuery("#error_date").html('Не заполнено поле Дата').css("display", "inline-block");
					if (focused == 0) { jQuery("#date").focus(); focused = 1; }
				}
				
				// var data = "do=<?=(isset($_GET["edit"]) && intval($_GET["edit"]) != 0 ? 'editEvent' : 'addEvent')?>&active="+jQuery("#active").val()+"&name="+jQuery("#name").val()+"&description="+jQuery("#description").val()+"&link="+jQuery("#link").val()+"&date="+jQuery("#date").val()+"<?=(isset($_GET["edit"]) && intval($_GET["edit"]) != 0 ? '&edit='.intval($_GET["edit"]) : '')?>&x=secure";
				// console.log(data);
				if (err_key == 0)
				{
					jQuery.ajax(
					{
						url: "ajax.php",
						data: "do=<?=(isset($_GET["edit"]) && intval($_GET["edit"]) != 0 ? 'editEvent' : 'addEvent')?>&active="+jQuery("#active").val()+"&name="+jQuery("#name").val()+"&description="+jQuery("#description").val()+"&link="+jQuery("#link").val()+"&date="+jQuery("#date").val()+"<?=(isset($_GET["edit"]) && intval($_GET["edit"]) != 0 ? '&edit='.intval($_GET["edit"]) : '')?>&x=secure",

						type: "POST",
						dataType : "html",
						cache: false,

						beforeSend: function()		{ jQuery("#protocol").html(""); jQuery(".action").hide(); jQuery(".loading").show(); },
						success:  function(data)	{ jQuery("#protocol").html(data); <?php /*?>jQuery(".action").show();<?php */?> jQuery(".loading").hide(); },
						error: function()			{ alert("Невозможно связаться с сервером"); jQuery(".action").show(); jQuery(".loading").hide(); }
					});
				}
			}

			<? if (isset($_GET["edit"]) && intval($_GET["edit"]) != 0) { ?>
			function deleteEvent()
			{
				var err_key = 0;
				var focused = 0;
				if (confirm("Вы действительно хотите удалить пользователя?"))
				{
					jQuery.ajax(
					{
						url: "ajax.php",
						data: "do=deleteEvent&edit=<?=intval($_GET["edit"])?>&x=secure",
						type: "POST",
						dataType : "html",
						cache: false,

						beforeSend: function()		{ jQuery("#protocol").html(""); jQuery("#action").hide(); },
						success:  function(data)	{ jQuery("#protocol").html(data); jQuery("#action").show(); },
						error: function()			{ alert("Невозможно связаться с сервером"); jQuery("#action").show(); }
					});
				}
			}
			<? } ?>

		</script>
		<?
	}
	else
		require($_SERVER["DOCUMENT_ROOT"]."/text_noAcces.php");
}
else
	require($_SERVER["DOCUMENT_ROOT"]."/text_noAuth.php");


require($_SERVER["DOCUMENT_ROOT"]."/libs/footer.php");?>
