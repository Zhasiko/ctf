
			$date = $api->Strings->date($lang,$r["create_date"],'sql','datetime');

			$status = ''; $class_st = '';
			switch (intval($r["status"])) {
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
			
			$status2 = ''; $class_st2 = '';
			switch (intval($r["status2"])) {				
				case 1:
					$status2 = 'новая'; $class_st2 = 'new';
					break;				
				case 3:
					$status2 = 'готова'; $class_st2 = 'done';
					break;				
			}

			$users = Array();
			$sU = mysql_query("SELECT * FROM `i_manager_users`");
			if (mysql_num_rows($sU) > 0)
			{	
				while($rU=mysql_fetch_array($sU))
				{
					$users[$rU["id"]]["name"] = stripslashes($rU["name"]);	
				}
			}

			$man_name = isset($users[$r["id_man"]]["name"]);
			$exam_name = isset($users[$r["id_exam"]]["name"]);
			$company_name = isset($users[$r["id_broker"]]["name"]);
			$broker_name = stripslashes($r["broker_name"]);
			
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
			
			?>
			<style>
				.btn { padding:.4rem 1.4rem }
			</style>
			<div class="card">  
				<div class="card-header">
					<div class="card-title" style="display:inline-block;">Информация по заявке:</div>
					<? 
					if (
						($api->Managers->man_block == 1 || $api->Managers->man_block == 5) ||
						($api->Managers->man_block == 2 && (intval($r["status"]) == 2 || intval($r["status"]) == 4))
					) { ?>
					<div class="but_del">
						<a class="btn btn-info" href="/order/add.php?edit=<?=$r["id"]?>">Редактировать поля</a>
					</div>
					<? } ?>
					<? if ($can_delete == 1) { ?>
					<div class="but_del" style="margin-right:15px">
						<button type="buton" class="btn btn-danger del_action" onclick="deleteZ();">Удалить</button>
						<span class="loading" id="load_del"><img src="/library/img/load.gif" /></span>
						<div id="protocolDel"></div>
					</div>
					<? } ?>
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
					<? if ($status2 != '') { ?>
					<div class="row mt-2">
						<label class="col-lg-4 col-md-3 col-sm-4 text-right">Статус №2:</label>
						<label class="col-lg-8 col-md-9 col-sm-8"><strong class="stat <?=$class_st2?>"><?=$status2?></strong></label>            
					</div>
					<? } ?>
					<div class="row mt-2">
						<label class="col-lg-4 col-md-3 col-sm-4 text-right">Компания:</label>
						<label class="col-lg-8 col-md-9 col-sm-8"><strong><?=$company_name?></strong></label>            
					</div>
					<? if ($broker_name != '') { ?>
					<div class="row mt-2">
						<label class="col-lg-4 col-md-3 col-sm-4 text-right">Брокер:</label>
						<label class="col-lg-8 col-md-9 col-sm-8"><strong><?=$broker_name?></strong></label>            
					</div>
					<? } ?>
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
						<label class="col-lg-8 col-md-9 col-sm-8"><strong><?=$r["user_name"]?></strong></label>            
					</div>
					<div class="row mt-2">
						<label class="col-lg-4 col-md-3 col-sm-4 text-right">ИИН:</label>
						<label class="col-lg-8 col-md-9 col-sm-8"><strong><?=$r["user_iin"]?></strong></label>            
					</div>
		<? if (
			$api->Managers->man_block == 1 || // админ 
			$api->Managers->man_block == 2 || $api->Managers->man_block == 5 || // менеджеры
			$api->Managers->man_block == 4 || // брокер
			$api->Managers->man_block == 3 // досмотрщик
		) { ?>
					<div class="row mt-2">
						<label class="col-lg-4 col-md-3 col-sm-4 text-right">Телефон:</label>
						<label class="col-lg-8 col-md-9 col-sm-8"><strong><?=$r["user_phone"]?></strong></label>            
					</div>
					<? if ($r["user_mail"] != '') { ?>
					<div class="row mt-2">
						<label class="col-lg-4 col-md-3 col-sm-4 text-right">E-mail:</label>
						<label class="col-lg-8 col-md-9 col-sm-8"><strong><?=$r["user_mail"]?></strong></label>            
					</div>
					<? } ?>
		<? } ?>
					<div class="row mt-2">
						<label class="col-lg-4 col-md-3 col-sm-4 text-right">Машина:</label>
						<label class="col-lg-8 col-md-9 col-sm-8"><strong><?=$r["car_type"]?>: <?=$r["mark"]?>, <?=$r["com_name"]?>, <?=$r["year"]?>, <?=$r["volume"]?></strong></label>            
					</div>
					<div class="row mt-2">
						<label class="col-lg-4 col-md-3 col-sm-4 text-right">VIN:</label>
						<label class="col-lg-8 col-md-9 col-sm-8"><strong><?=$r["vin"]?></strong></label>            
					</div>
					<div class="row mt-2">
						<label class="col-lg-4 col-md-3 col-sm-4 text-right">СВХ:</label>
						<label class="col-lg-8 col-md-9 col-sm-8"><strong><?=$r["svh"]?></strong></label>            
					</div>
					<div class="row mt-2">
						<label class="col-lg-4 col-md-3 col-sm-4 text-right">УВЭОС (СОС кнопка):</label>
						<label class="col-lg-8 col-md-9 col-sm-8"><strong><?=(intval($r["uvesos"])==1 ? 'да' : 'нет')?></strong></label>            
					</div>
				</div>
				<?
				$sFB = mysql_query("SELECT * FROM `i_foto_broker` WHERE `id_order`='".$id."' LIMIT 1");
				if (mysql_num_rows($sFB) > 0)
				{
					$rFB=mysql_fetch_array($sFB);
				?>
				<div class="card-header" style="border-top:1px solid #ebecec !important">
					<div class="card-title" style="text-align:left;">Фото заявителя:</div>
				</div>
				<?
				$foto_name_br = Array();
				$foto_name_br[1] = 'ТЕХПАСПОРТ СПЕРЕДИ';
				$foto_name_br[2] = 'ТЕХПАСПОРТ СЗАДИ';
				$foto_name_br[3] = 'ИНВОЙС';
				$foto_name_br[4] = 'СОПРОВОДИТЕЛЬНЫЕ ДОКУМЕНТЫ';
				$foto_name_br[5] = 'УДОСТОВЕРЕНИЕ ЛИЧНОСТИ СПЕРЕДИ';
				$foto_name_br[6] = 'УДОСТОВЕРЕНИЕ ЛИЧНОСТИ СЗАДИ';
				$foto_name_br[7] = 'ПРОЧИЕ';
				$foto_name_br[8] = 'ПРОЧИЕ';
				$foto_name_br[9] = 'ПРОЧИЕ';
				$foto_name_br[10] = 'ПРОЧИЕ';
				?>
				<div class="card-body more_mob">					
					<? for($i=1; $i<=10; $i++) { ?>
						<? if ($rFB["foto_".$i] != '') { ?>
					<div class="row mt-2">
						<label for="link_man" class="col-lg-4 col-md-3 col-sm-4 text-right"><?=$foto_name_br[$i]?></label>
						<label class="col-lg-8 col-md-9 col-sm-8">
							<strong class="stat"><a href="/upload/foto_broker/<?=$rFB["foto_".$i]?>" target="_blank"><?=$rFB["foto_".$i]?></a></strong>
						</label>						
					</div>	
						<? } ?>
					<? } ?>
				</div>
				<?
				}
				?>
<? 
if (
	$api->Managers->man_block == 1 || // админ 
	$api->Managers->man_block == 2 || $api->Managers->man_block == 5 // менеджеры
) {		
?>
				<div class="card-header" style="border-top:1px solid #ebecec !important">
					<div class="card-title" style="text-align:left;">Действия:</div>
				</div>
				<div class="card-body links_bl">
					<div class="form-group form-show-validation row">
						<label for="user_phone" class="col-lg-4 col-md-3 col-sm-4 mt-sm-2 text-right">Whatsapp заявителя <span class="required-label">*</span></label>
						<div class="col-lg-2 col-md-2 col-sm-2">
							<input type="text" class="form-control phone" id="user_phone" value="<?=$r["user_phone"]?>" style="padding:.6rem .6rem" />
						</div>			
						<div class="col-lg-4 col-md-4 col-sm-4">
							<button class="btn btn-success action" id="sendWh" onclick="sendWh();">Отправить на whatsapp</button>
							<div class="loading" id="load_Wh">
								<img src="/library/img/load.gif" /> Ваш запрос обрабатывается...
							</div>
							<div id="protocolWh"></div>
						</div>
					</div>
					<div class="form-group form-show-validation row">
						<label for="date_issue" class="col-lg-4 col-md-3 col-sm-4 mt-sm-2 text-right">Дата выпуска</label>
						<div class="col-lg-2 col-md-2 col-sm-2">
							<input type="text" class="form-control dateInput" id="date_issue" value="<?=$r["date_issue"]?>" />
						</div>			
						<div class="col-lg-4 col-md-4 col-sm-4">
							<button class="btn btn-secondary action" id="saveDateI" onclick="saveDateI();">Сохранить дату выпуска</button>
							<div class="loading" id="load_DI">
								<img src="/library/img/load.gif" /> Ваш запрос обрабатывается...
							</div>
							<div id="protocolDI"></div>
						</div>
					</div>	
					<? 
					if (
						($api->Managers->man_block == 1 || $api->Managers->man_block == 5) ||
						($api->Managers->man_block == 2 && intval($r["status"]) >= 2)
					) { ?>
					<div class="form-group form-show-validation row">
						<label for="link_man" class="col-lg-4 col-md-3 col-sm-4 mt-sm-2 text-right">Ссылка №1 на сайт <span class="required-label">*</span></label>
						<div class="col-lg-7 col-md-9 col-sm-8">
							<input type="text" class="form-control" id="link_man" value="<?=$r["link_man"]?>" />
						</div>						
					</div>	
					<div class="form-group form-show-validation row">
						<label for="link_man2" class="col-lg-4 col-md-3 col-sm-4 mt-sm-2 text-right">Ссылка №2 на сайт <span class="required-label">*</span></label>
						<div class="col-lg-7 col-md-9 col-sm-8">
							<input type="text" class="form-control" id="link_man2" value="<?=$r["link_man2"]?>" />
						</div>						
					</div>	
					<div class="form-group form-show-validation row">						
						<div class="col-lg-11 col-md-11 col-sm-12 text-right">
							<button class="btn btn-secondary action" id="saveFields" onclick="saveFields();">Сохранить ссылки</button>
							<div class="loading" id="load_SF">
								<img src="/library/img/load.gif" /> Ваш запрос обрабатывается...
							</div>
							<div id="protocolSF"></div>
						</div>						
					</div>
					<? } ?>
					<div class="form-group form-show-validation row">
						<label for="status" class="col-lg-4 col-md-3 col-sm-4 mt-sm-2 text-right">Статус заявки</label>
						<div class="col-lg-4 col-md-9 col-sm-4">
							<select class="form-control" id="status" onchange="saveStatus();">
								<? if ($api->Managers->man_block != 2) { ?>
								<option value="0"<?=(intval($r["status"])==0 ? ' selected' : '')?>> на одобрение </option>
								<? } ?>
								<option value="1"<?=(intval($r["status"])==1 ? ' selected' : '')?>> новая </option>
								<option value="2"<?=(intval($r["status"])==2 ? ' selected' : '')?>> в работе </option>
								<option value="3"<?=(intval($r["status"])==3 ? ' selected' : '')?>> готова </option>
								<? if (intval($r["status"]) == 4) { ?>
								<option selected> изменено досмотрщиком </option>
								<? } ?>
							</select>
							<div id="prStatus"></div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2">		
							<div id="l_of" class="loading"><img src="/library/img/load.gif"></div>	    
						</div>
					</div>											
					
					<div id="pdf_block"<?=(intval($r["status"])==3 ? '' : ' style="display:none"')?>>					
						<div class="form-group form-show-validation row">
							<label for="pdf_num" class="col-lg-4 col-md-3 col-sm-4 mt-sm-2 text-right">№ <span class="required-label">*</span></label>
							<div class="col-lg-4 col-md-9 col-sm-4">
								<input type="text" class="form-control" id="pdf_num" value="<?=$r["pdf_num"]?>" />
							</div>						
						</div>	
						<div class="form-group form-show-validation row">
							<label for="pdf_seriya" class="col-lg-4 col-md-3 col-sm-4 mt-sm-2 text-right">Серия KZ <span class="required-label">*</span></label>
							<div class="col-lg-4 col-md-9 col-sm-4">
								<input type="text" class="form-control" id="pdf_seriya" value="<?=$r["pdf_seriya"]?>" />
							</div>						
						</div>	
						<div class="form-group form-show-validation row">
							<label for="date_oform" class="col-lg-4 col-md-3 col-sm-4 mt-sm-2 text-right">Дата оформления <span class="required-label">*</span></label>
							<div class="col-lg-3 col-md-3 col-sm-3">
								<input type="text" class="form-control dateInput" id="date_oform" value="<?=$r["date_oform"]?>" />
							</div>						
						</div>
						<div class="form-group form-show-validation row">
							<label for="id_temp" class="col-lg-4 col-md-3 col-sm-4 mt-sm-2 text-right">Шаблон</label>
							<div class="col-lg-4 col-md-9 col-sm-4">
								<select class="form-control" id="id_temp">
									<option value=""> выберите шаблон </option>
									<?
									$sT=mysql_query("SELECT * FROM `i_temp_pdf` WHERE `name_temp` IS NOT NULL ORDER BY `name` ASC");
									if (mysql_num_rows($sT) > 0)
									{
										while($rT=mysql_fetch_array($sT))
										{
									?><option value="<?=$rT["id"]?>"<?=($rT["id"]==$r["id_temp"] ? ' selected' : '')?>> <?=stripslashes($rT["name_temp"])?> / <?=$api->Strings->pr_plus(htmlspecialchars_decode(stripslashes($rT["name"])))?> </option><?		
										}
									}
									?>							
								</select>								
							</div>																
							<div class="col-lg-3 col-md-3 col-sm-3 text-right">
								<button class="btn btn-danger action" id="getPDF" onclick="getFileTo('PDF');">Скачать PDF</button>
								&nbsp;&nbsp;
								<button class="btn btn-success action" id="getEXCEL" onclick="getFileTo('EXCEL');">Скачать Excel</button>
								<div class="loading" id="load_File">
									<img src="/library/img/load.gif" /> Ваш запрос обрабатывается...
								</div>
								<div id="protocolFile"></div>
							</div>						
						</div>					
					</div>
					
					<div style="display:none">
						<div class="form-group form-show-validation row">
							<label for="id_temp" class="col-lg-4 col-md-3 col-sm-4"></label>
							<div class="col-lg-7 col-md-9 col-sm-8 mt-sm-2 mb-sm-2 text-right">
								<button class="btn btn-danger actionPDF" onclick="getPDF('1');">Скачать PDF «Протокол»</button>
								&nbsp;&nbsp;
								<button class="btn btn-danger actionPDF" onclick="getPDF('2');">Скачать PDF «Решение»</button>
								&nbsp;&nbsp;
								<button class="btn btn-danger actionPDF" onclick="getPDF('3');">Скачать PDF «Заявка»</button>
								&nbsp;&nbsp;
								<button class="btn btn-danger actionPDF" onclick="getPDF('4');">Скачать PDF «Договор»</button>

								<div class="loading" id="loadPDF">
									<img src="/library/img/load.gif" /> Ваш запрос обрабатывается...
								</div>
								<div id="protocolPDF"></div>
							</div>
						</div>
					</div>
					
					<div id="status2_block"<?=(intval($r["status"])==3 ? '' : ' style="display:none"')?>>	
						<div class="form-group form-show-validation row">
							<label for="status2" class="col-lg-4 col-md-3 col-sm-4 mt-sm-2 text-right">Статус №2 заявки</label>
							<div class="col-lg-4 col-md-9 col-sm-4">
								<select class="form-control" id="status2" onchange="saveStatus2();">									
									<option value="1"<?=(intval($r["status2"])==1 ? ' selected' : '')?>> новая </option>									
									<option value="3"<?=(intval($r["status2"])==3 ? ' selected' : '')?>> готова </option>									
								</select>
								<div id="prStatus2"></div>
							</div>
							<div class="col-lg-2 col-md-2 col-sm-2">		
								<div id="l_of2" class="loading"><img src="/library/img/load.gif"></div>	    
							</div>
						</div>
					</div>
					
				</div>				
				<script type="text/javascript">
					
					function sendWh()
					{		
						var err_key = 0;
						var focused = 0;

						jQuery(".links_bl .form-control").css("border-color", "#c9cbcd");
						
						var phone = jQuery("#user_phone").val();
						phone = phone.replace(/_/g, "");
						if (jQuery("#user_phone").val()=="" || phone.length != 14)
						{
							err_key = 1;					
							jQuery("#user_phone").css("border-color", "#f00");							
							if (focused == 0) { jQuery("#user_phone").focus(); focused = 1; }
						}												
						
						if (err_key == 0) 			
						{											
							jQuery.ajax(
							{
								url: "ajax.php",
								data: "do=sendWh&user_phone="+jQuery("#user_phone").val()+"&id=<?=$id?>&x=secure",
								type: "POST",
								dataType : "html",
								cache: false,
								
								beforeSend: function()		{ jQuery("#sendWh").hide(); jQuery("#load_Wh").show(); },
								success:  function(data)  	{ jQuery("#protocolWh").html(data); jQuery("#sendWh").show(); jQuery("#load_Wh").hide(); },
								error: function()         	{ jQuery("#protocolWh").html("<p style='color:#f00'>Невозможно связаться с сервером!</p>"); jQuery("#sendWh").show(); jQuery("#load_Wh").hide(); }
							});															
						}						
					}
					
					function saveStatus()
					{													
						jQuery.ajax(
						{
							url: "ajax.php",
							data: "do=saveStatus&status="+jQuery("#status").val()+"&id=<?=$id?>&x=secure",
							type: "POST",
							dataType : "html",
							cache: false,
							
							beforeSend: function()		{ jQuery("#l_of").show(); },
							success:  function(data)  	{ jQuery("#prStatus").html(data); jQuery("#l_of").hide(); },
							error: function()         	{ jQuery("#prStatus").html("<span style='color:#f00'>Невозможно связаться с сервером!</span>"); jQuery("#l_of").hide(); }
						});															
					}
					
					function saveStatus2()
					{													
						jQuery.ajax(
						{
							url: "ajax.php",
							data: "do=saveStatus2&status="+jQuery("#status2").val()+"&id=<?=$id?>&x=secure",
							type: "POST",
							dataType : "html",
							cache: false,
							
							beforeSend: function()		{ jQuery("#l_of2").show(); },
							success:  function(data)  	{ jQuery("#prStatus2").html(data); jQuery("#l_of2").hide(); },
							error: function()         	{ jQuery("#prStatus2").html("<span style='color:#f00'>Невозможно связаться с сервером!</span>"); jQuery("#l_of2").hide(); }
						});															
					}
					
					function saveDateI()
					{																		
						jQuery.ajax(
						{
							url: "ajax.php",
							data: "do=saveDateI&date_issue="+jQuery("#date_issue").val()+"&id=<?=$id?>&x=secure",
							type: "POST",
							dataType : "html",
							cache: false,

							beforeSend: function()		{ jQuery("#saveDateI").hide(); jQuery("#load_DI").show(); },
							success:  function(data)  	{ jQuery("#protocolDI").html(data); jQuery("#saveDateI").show(); jQuery("#load_DI").hide(); },
							error: function()         	{ jQuery("#protocolDI").html("<p style='color:#f00'>Невозможно связаться с сервером!</p>"); jQuery("#saveDateI").show(); jQuery("#load_DI").hide(); }
						});																										
					}
					
					function saveFields()
					{		
						var err_key = 0;
						var focused = 0;

						jQuery(".links_bl .form-control").css("border-color", "#c9cbcd");
						
						if (jQuery("#link_man").val() == '')
						{
							err_key = 1;					
							jQuery("#link_man").css("border-color", "#f00");							
							if (focused == 0) { jQuery("#link_man").focus(); focused = 1; }
						}
						
						if (jQuery("#link_man2").val() == '')
						{
							err_key = 1;					
							jQuery("#link_man2").css("border-color", "#f00");							
							if (focused == 0) { jQuery("#link_man2").focus(); focused = 1; }
						}
						
						if (err_key == 0) 			
						{											
							jQuery.ajax(
							{
								url: "ajax.php",
								data: "do=saveFields&link_man="+jQuery("#link_man").val()+"&link_man2="+jQuery("#link_man2").val()+"&id=<?=$id?>&x=secure",
								type: "POST",
								dataType : "html",
								cache: false,
								
								beforeSend: function()		{ jQuery("#saveFields").hide(); jQuery("#load_SF").show(); },
								success:  function(data)  	{ jQuery("#protocolSF").html(data); jQuery("#saveFields").show(); jQuery("#load_SF").hide(); },
								error: function()         	{ jQuery("#protocolSF").html("<p style='color:#f00'>Невозможно связаться с сервером!</p>"); jQuery("#saveFields").show(); jQuery("#load_SF").hide(); }
							});															
						}						
					}
					
					<? if ($can_delete == 1) { ?>
					function deleteZ()
					{
						var err_key = 0;
						var focused = 0;

						if (confirm("Вы действительно хотите удалить запись?"))
						{
							jQuery.ajax(
							{
								url: "ajax.php",
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
					<? } ?>
					
					function getFileTo(file)
					{
						var err_key = 0;
						var focused = 0;

						jQuery(".links_bl .form-control").css("border-color", "#c9cbcd");
						
						if (jQuery("#pdf_num").val() == '')
						{
							err_key = 1;					
							jQuery("#pdf_num").css("border-color", "#f00");							
							if (focused == 0) { jQuery("#pdf_num").focus(); focused = 1; }
						}
						
						if (jQuery("#pdf_seriya").val() == '')
						{
							err_key = 1;					
							jQuery("#pdf_seriya").css("border-color", "#f00");							
							if (focused == 0) { jQuery("#pdf_seriya").focus(); focused = 1; }
						}
						
						if (jQuery("#date_oform").val() == '')
						{
							err_key = 1;					
							jQuery("#date_oform").css("border-color", "#f00");							
							if (focused == 0) { jQuery("#date_oform").focus(); focused = 1; }
						}
						
						if (jQuery("#id_temp").val() == '')
						{
							err_key = 1;					
							jQuery("#id_temp").css("border-color", "#f00");							
							if (focused == 0) { jQuery("#id_temp").focus(); focused = 1; }
						}
						
						if (err_key == 0) 			
						{											
							jQuery.ajax(
							{
								url: "ajax.php",
								data: "do=getFile&pdf_num="+jQuery("#pdf_num").val()+"&pdf_seriya="+jQuery("#pdf_seriya").val()+"&date_oform="+jQuery("#date_oform").val()+"&id_temp="+jQuery("#id_temp").val()+"&file="+file+"&id=<?=$id?>&x=secure",
								type: "POST",
								dataType : "html",
								cache: false,
								
								beforeSend: function()		{ jQuery("#getPDF").hide(); jQuery("#getEXCEL").hide(); jQuery("#load_File").show(); },
								success:  function(data)  	{ jQuery("#protocolFile").html(data); jQuery("#getPDF").show(); jQuery("#getEXCEL").show(); jQuery("#load_File").hide(); },
								error: function()         	{ jQuery("#protocolFile").html("<p style='color:#f00'>Невозможно связаться с сервером!</p>"); jQuery("#getPDF").show(); jQuery("#getEXCEL").show();jQuery("#load_File").hide(); }
							});															
						}	
					}
					
					function getPDF(file)
					{
						var err_key = 0;
						var focused = 0;

						jQuery(".links_bl .form-control").css("border-color", "#c9cbcd");
						
						if (jQuery("#pdf_num").val() == '')
						{
							err_key = 1;					
							jQuery("#pdf_num").css("border-color", "#f00");							
							if (focused == 0) { jQuery("#pdf_num").focus(); focused = 1; }
						}
						
						if (jQuery("#pdf_seriya").val() == '')
						{
							err_key = 1;					
							jQuery("#pdf_seriya").css("border-color", "#f00");							
							if (focused == 0) { jQuery("#pdf_seriya").focus(); focused = 1; }
						}
						
						if (jQuery("#date_oform").val() == '')
						{
							err_key = 1;					
							jQuery("#date_oform").css("border-color", "#f00");							
							if (focused == 0) { jQuery("#date_oform").focus(); focused = 1; }
						}
						
						if (jQuery("#id_temp").val() == '')
						{
							err_key = 1;					
							jQuery("#id_temp").css("border-color", "#f00");							
							if (focused == 0) { jQuery("#id_temp").focus(); focused = 1; }
						}
						
						if (err_key == 0) 			
						{											
							jQuery.ajax(
							{
								url: "ajax.php",
								data: "do=getPDF&pdf_num="+jQuery("#pdf_num").val()+"&pdf_seriya="+jQuery("#pdf_seriya").val()+"&date_oform="+jQuery("#date_oform").val()+"&id_temp="+jQuery("#id_temp").val()+"&file="+file+"&id=<?=$id?>&x=secure",
								type: "POST",
								dataType : "html",
								cache: false,
								
								beforeSend: function()		{ jQuery(".actionPDF").hide(); jQuery("#loadPDF").show(); },
								success:  function(data)  	{ jQuery("#protocolPDF").html(data); jQuery(".actionPDF").show(); jQuery("#loadPDF").hide(); },
								error: function()         	{ jQuery("#protocolPDF").html("<p style='color:#f00'>Невозможно связаться с сервером!</p>"); jQuery(".actionPDF").show(); jQuery("#loadPDF").hide(); }
							});															
						}	
					}
					
				</script>
				<?
				$link_video = '';
				$sF = mysql_query("SELECT * FROM `i_foto` WHERE `id_order`='".$id."' LIMIT 1");
				if (mysql_num_rows($sF) > 0)
				{
					$rF=mysql_fetch_array($sF);
					
					?>
					<div class="card-header" style="border-top:1px solid #ebecec !important">
						<div class="card-title" style="text-align:left;">Фото и видео от Досмотрщика:</div>
					</div>
					<div class="card-body links_bl">
					
					<?					
					for($i=1; $i<=12; $i++)
					{
						?>
						<div class="row mt-2">
							<label class="col-lg-4 col-md-3 col-sm-4<?=($api->Strings->check_smartphone() == true ? '' : ' text-right')?>"><?=$foto_name[$i]?>:</label>
							<label class="col-lg-8 col-md-9 col-sm-8"><strong class="stat"><a href="/upload/foto/<?=$rF["foto_".$i]?>" target="_blank"><?=$rF["foto_".$i]?></a></strong></label>            
						</div>						
						<?
					}
						
						?>
						<div class="row mt-2">
							<label class="col-lg-4 col-md-3 col-sm-4<?=($api->Strings->check_smartphone() == true ? '' : ' text-right')?>">Ссылка видео:</label>
							<label class="col-lg-8 col-md-9 col-sm-8"><strong class="stat"><a href="<?=$rF["link_video"]?>" target="_blank"><?=$rF["link_video"]?></a></strong></label>            
						</div>						
					</div>	
					<?
				}
				?>
				
<? } // ДЕЙСТВИЯ менеджера ============  

if ($api->Managers->man_block == 3) {		
?>
				<div class="card-header" style="border-top:1px solid #ebecec !important">
					<div class="card-title" style="text-align:left;">Действия:</div>
				</div>
				<div class="card-body links_bl">
					<?										
					$foto = Array();
					for($i=1; $i<=12; $i++)
						$foto[$i] = '';
					
					$link_video = '';
					$sF = mysql_query("SELECT * FROM `i_foto` WHERE `id_order`='".$id."' LIMIT 1");
					if (mysql_num_rows($sF) > 0)
					{
						$rF=mysql_fetch_array($sF);
						
						for($i=1; $i<=12; $i++)
							$foto[$i] = $rF["foto_".$i];
						
						$link_video = $rF["link_video"];
					}
	
					for($i=1; $i<=12; $i++)
					{
						?>
						<div class="form-group form-show-validation row">
							<label for="status" class="col-lg-6 col-md-6 col-sm-6 mt-sm-2 text-right"><?=$foto_name[$i]?></label>
							<div class="col-lg-6 col-md-6 col-sm-6">
								<div class="fileUpload" id="add_foto<?=$i?>"<?=($foto[$i] != '' ? ' style="display:none"' : '')?>>
									<button type="button" class="btn btn-success">Загрузить</button>
									<div class="fileUpload-outerWrap">
										<div class="fileUpload-innerWrap">
											<iframe id="uploadFrame<?=$i?>" name="uploadFrame<?=$i?>" style="display:none"></iframe>
											<form name="form" id="photo<?=$i?>" target="uploadFrame<?=$i?>" action="/order/more_foto.php" method="post" enctype="multipart/form-data">
												<input class="fileUpload-input" name="img<?=$i?>" id="pac_img<?=$i?>" type="file" onchange="sub_f('<?=$i?>');"  />
												<input class="forms" type="hidden" name="image<?=$i?>" id="image<?=$i?>" />
												<input class="forms" type="hidden" name="order" id="order" value="<?=$id?>" />
												<input class="forms" type="hidden" name="count" id="count" value="<?=$i?>" />
											</form>
										</div>
									</div>
								</div>
								<div class="mt-2" id="delete_foto<?=$i?>"<?=($foto[$i] != '' ? '' : ' style="display:none"')?>>
									<span id="load_to_foto<?=$i?>"><a href="/upload/foto/<?=$foto[$i]?>" target="_blank"><?=$foto[$i]?></a></span> – 
									<span class="link" onclick="deleteFoto('<?=$i?>')">Удалить</span>
								</div>
								<span id="loader<?=$i?>" style="display:none"><img src="/library/img/load.gif" /></span>
							</div>
						</div>	
					<? } ?>
					<div id="protocolDelete"></div>
					<div class="form-group form-show-validation row">
						<label for="link_video" class="col-lg-2 col-md-3 col-sm-4 mt-sm-2 text-right">Ссылка видео</label>
						<div class="col-lg-6 col-md-8 col-sm-8">
							<input type="text" class="form-control" id="link_video" value="<?=$link_video?>" />
						</div>		
						<div class="col-lg-4 col-md-4 col-sm-5">
							<button class="btn btn-success action" id="saveVideo" onclick="saveVideo();">Сохранить</button>
							<div class="loading" id="load_SV">
								<img src="/library/img/load.gif" /> Ваш запрос обрабатывается...
							</div>
							<div id="protocolSV"></div>
						</div>
					</div>						
					<script type="text/javascript">
						
						function onResponseCab(d) 
						{  
							eval('var obj = ' + d + ';');
							if (obj.success) 
							{
								var type = obj.img;
								
								jQuery("#add_foto"+type).hide();
								jQuery("#delete_foto"+type).show();	
								jQuery("#load_to_foto"+type).html("<a href='/upload/foto/"+obj.name+"' target='_blank'>"+obj.name+"</a>");
								jQuery("#loader"+obj.img).hide();
							}
							else { alert(obj.erorr); jQuery("#loader"+obj.img).hide(); jQuery("#add_foto"+obj.img).show(); jQuery('#pac_img'+obj.img).val(""); }
						}

						function sub_f(number) { jQuery("#loader"+number).show(); jQuery("#add_foto"+number).hide(); jQuery('#photo'+number).submit(); }

						function deleteFoto(type)
						{
							var foto_name = Array();
							foto_name[1] = 'ВИД СПЕРЕДИ';
							foto_name[2] = 'ВИД СПРАВА';
							foto_name[3] = 'ВИД СЗАДИ';
							foto_name[4] = 'ВИД СЛЕВА';
							foto_name[5] = 'ШИЛЬДИК (БИРКА С ВИН И ДАТОЙ ВЫПУСКА)';
							foto_name[6] = 'БИРКА НА РЕМНЕ БЕЗОПАСНОСТИ';
							foto_name[7] = 'МАРКА И НОМЕР ДВИГАТЕЛЯ';
							foto_name[8] = 'ФОТО РАСПОЛОЖЕНИЯ ДВИГАТЕЛЯ';
							foto_name[9] = 'ФОТО ГЛУШИТЕЛЕЙ';
							foto_name[10] = 'ФОТО СЕЛЕКТОРА КОРОБКИ ПЕРЕДАЧ И СТОЯНОЧНОГО ТОРМОЗА';
							foto_name[11] = 'ФОТО ШИН';
							foto_name[12] = 'ФОТО САЛОНА (КОЛИЧЕСТВО МЕСТ)';	

							if (confirm("Вы действительно хотите удалить фото «"+foto_name[type]+"»?"))
							{
								jQuery.ajax({
									url: "ajax.php",
									data: "do=deleteFoto&type="+type+"&id=<?=$id?>&x=secure",
									type: "POST",
									dataType : "html",
									cache: false,
									
									beforeSend: function()		{ jQuery("#delete_foto"+type).hide(); jQuery("#loader"+type).show(); },
									success:  function(data)  	{ jQuery("#protocolDelete").html(data); jQuery("#loader"+type).hide(); },
									error: function()         	{ jQuery("#protocolDelete").html("<p style='color:#f00'>Невозможно связаться с сервером!</p>"); jQuery("#delete_foto"+type).show(); jQuery("#loader"+type).hide(); }																		
								});
							}
						}
						
						function saveVideo()
						{		
							var err_key = 0;
							var focused = 0;

							jQuery(".links_bl .form-control").css("border-color", "#c9cbcd");

							if (jQuery("#link_video").val() == '')
							{
								err_key = 1;					
								jQuery("#link_video").css("border-color", "#f00");							
								if (focused == 0) { jQuery("#link_video").focus(); focused = 1; }
							}
							
							if (err_key == 0) 			
							{											
								jQuery.ajax(
								{
									url: "ajax.php",
									data: "do=saveVideo&link_video="+jQuery("#link_video").val()+"&id=<?=$id?>&x=secure",
									type: "POST",
									dataType : "html",
									cache: false,

									beforeSend: function()		{ jQuery("#saveVideo").hide(); jQuery("#load_SV").show(); },
									success:  function(data)  	{ jQuery("#protocolSV").html(data); jQuery("#saveVideo").show(); jQuery("#load_SV").hide(); },
									error: function()         	{ jQuery("#protocolSV").html("<p style='color:#f00'>Невозможно связаться с сервером!</p>"); jQuery("#saveVideo").show(); jQuery("#load_SV").hide(); }
								});															
							}						
						}
						
					</script>
				</div>

<?