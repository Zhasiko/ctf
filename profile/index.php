<?

require_once '../get_encr_key.php';


$lang="ru";
$title="Добавить пользователя";
if (isset($_GET["edit"]) && intval($_GET["edit"])!=0)
	$title="Редактировать пользователя";
$keywords="";
$description="";
require($_SERVER["DOCUMENT_ROOT"]."/libs/header.php");
if ($api->Managers->check_auth() == true)
{
	if ($api->Managers->man_block == 3)
	{
        $user_id = $api->Managers->man_id;
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
                $cat_value = '';
                $user_name_value = '';
                $login_value = '';
				$timestamp_x = '';
				$phone_value = '';
                // if (isset($_GET["edit"]) && intval($_GET["edit"]) != 0)
                // {
					$sql_ = '';										
                    $s=mysql_query("SELECT * FROM `i_manager_users` WHERE `id`='".$user_id."'".$sql_." ORDER BY `id` ASC LIMIT 1");
                    if (mysql_num_rows($s) > 0)
                    {
                        $r=mysql_fetch_array($s);

                        $active_value = intval($r["active"]);
                        $cat_value = $r["id_section"];
                        $user_name_value = stripslashes($r["name"]);
                        $login_value = $r["login"];
                        $password_value = decryptPassword($r["pass"], $encryption_key);
						// $hashed_password = password_hash($password_value, PASSWORD_DEFAULT);
						$timestamp_x = $r["timestamp_x"];
						$phone_value = $r["phone"];
                    }
                // }
				// else
				// {
					function randomPassword() {
						//$alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
						$alphabet = 'abcdefghijklmnopqrstuvwxyz1234567890';
						$pass = array(); //remember to declare $pass as an array
						$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
						for ($i = 0; $i < 4; $i++) {
							$n = rand(0, $alphaLength);
							$pass[] = $alphabet[$n];
						}
						return implode($pass); //turn the array into a string
					}

					$password_value = randomPassword();
					
					$hashed_password = password_hash($password_value, PASSWORD_DEFAULT);
                ?>
                <?php
                    $role = '';
                    if ($cat_value == 1) {
                        $role = 'Админ';
                    } elseif ($cat_value == 2) {
                        $role = 'Преподователь';
                    } elseif ($cat_value == 3) {
                        $role = 'Студент';
                    }
                ?>

                <div class="form-group form-show-validation row">
                    <label for="cat" class="col-lg-4 col-md-3 col-sm-4 mt-sm-2 text-right" style = "color: white !important;" >Роль </label>
                    <div class="col-lg-4 col-md-9 col-sm-8">
                        <strong><?= $role ?></strong>
                        <span class="control__help" id="error_cat"></span>
                	</div>
            	</div>

				<div class="form-group form-show-validation row">
                    <label for="name" class="col-lg-4 col-md-3 col-sm-4 mt-sm-2 text-right" style = "color: white !important;">ФИ</label>
                    <div class="col-lg-4 col-md-9 col-sm-8">
                        <strong><?= $user_name_value ?></strong>
                        <span class="control__help" id="error_name"></span>
                    </div>
                </div>
				
				<div class="form-group form-show-validation row">
                    <label for="phone" class="col-lg-4 col-md-3 col-sm-4 mt-sm-2 text-right" style = "color: white !important;">Телефон</label>
                    <div class="col-lg-4 col-md-9 col-sm-8">
                        <strong class="form-control phone"><?= $phone_value ?></strong>
                    </div>
                </div>

				
                <div class="form-group form-show-validation row">
                    <label for="mail" class="col-lg-4 col-md-3 col-sm-4 mt-sm-2 text-right" style = "color: white !important;">Логин</label>
                    <div class="col-lg-4 col-md-9 col-sm-8">
                        <!-- <input type="text" class="form-control" id="login" value="<?=$login_value?>"  /> -->
                        <strong class="form-control"><?= $login_value ?></strong>

                        <span class="control__help" id="error_login"></span>
                    </div>
                </div>
				
			</div>

		</div>
		<script type="text/javascript">

			function addUser()
			{
				var err_key = 0;
				var focused = 0;

				jQuery(".calc__card input").css("border-color", "#c9cbcd");
				jQuery(".control__help").html('').hide();

				if (jQuery("#cat").val() == '')
				{
					err_key = 1;
					jQuery("#cat").css("border-color", "#f00");
					jQuery("#error_cat").html('Не выбрана категория').css("display", "inline-block");
					if (focused == 0) { jQuery("#cat").focus(); focused = 1; }
				}

				if (jQuery("#name").val() == '')
				{
					err_key = 1;
					jQuery("#name").css("border-color", "#f00");
					jQuery("#error_name").html('Не заполнено поле ФИ').css("display", "inline-block");
					if (focused == 0) { jQuery("#name").focus(); focused = 1; }
				}

				<?php /*?>var phone = jQuery("#login").val();
				phone = phone.replace(/_/g, "");<?php */?>
				if (jQuery("#login").val()=="" <?php /*?>|| phone.length != 14<?php */?>)
				{
					err_key = 1;
					jQuery("#error_login").html('Не заполнено поле Логин').css("display", "inline-block");
					jQuery("#login").css("border-color", "#f00");
					if (focused == 0) { jQuery("#login").focus(); focused = 1; }
				}

				if (jQuery("#pass").val() == '')
				{
					err_key = 1;
					jQuery("#pass").css("border-color", "#f00");
					jQuery("#error_pass").html('Не заполнено поле Пароль').css("display", "inline-block");
					if (focused == 0) { jQuery("#pass").focus(); focused = 1; }
				}
				
				var phone = jQuery("#phone").val();
				phone = phone.replace(/_/g, "");
				if (jQuery("#phone").val()=="" || phone.length != 14)
				{
					err_key = 1;
					jQuery("#error_phone").html('Не верно заполнено поле Телефон').css("display", "inline-block");
					jQuery("#phone").css("border-color", "#f00");
					if (focused == 0) { jQuery("#phone").focus(); focused = 1; }
				}

				if (err_key == 0)
				{
					jQuery.ajax(
					{
						url: "ajax.php",
						data: "do=<?=(isset($_GET["edit"]) && intval($_GET["edit"]) != 0 ? 'editUser' : 'addUser')?>&active="+jQuery("#active").val()+"&cat="+jQuery("#cat").val()+"&name="+jQuery("#name").val()+"&phone="+jQuery("#phone").val()+"&login="+jQuery("#login").val()+"&pass="+ jQuery("#pass").val() +"<?=(isset($_GET["edit"]) && intval($_GET["edit"]) != 0 ? '&edit='.intval($_GET["edit"]) : '')?>&x=secure",
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
			function deleteUser()
			{
				var err_key = 0;
				var focused = 0;

				if (confirm("Вы действительно хотите удалить пользователя?"))
				{
					jQuery.ajax(
					{
						url: "ajax.php",
						data: "do=deleteUser&edit=<?=intval($_GET["edit"])?>&x=secure",
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
