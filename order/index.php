<?
$lang="ru";
$title="Заявки";
$keywords="";
$description="";
require($_SERVER["DOCUMENT_ROOT"]."/libs/header.php");

if ($api->Managers->check_auth() == true)
{
	if (
		$api->Managers->man_block == 1 || // админ 
		$api->Managers->man_block == 2 || $api->Managers->man_block == 5 || // менеджеры
		$api->Managers->man_block == 3 || // досмотрщик
		$api->Managers->man_block == 4 // брокер
	)
	{		
		$php_self = '/order/';
		$get_status = '?'.preg_replace('#(^|&)status=[0-9]+(|$)#', '', $_SERVER['QUERY_STRING']);
		$get_exam = '?'.preg_replace('#(^|&)exam=[0-9]+(|$)#', '', $_SERVER['QUERY_STRING']);
		$get_broker = '?'.preg_replace('#(^|&)broker=[0-9]+(|$)#', '', $_SERVER['QUERY_STRING']);
		
		$sql_get = "";
		
		$dateSS = '';
		$dateDD = '';

		if (isset($_GET["dateSS"]) && $api->Strings->pr($_GET["dateSS"]) != '')
			$dateSS = $api->Strings->pr($_GET["dateSS"]);
		if (isset($_GET["dateDD"]) && $api->Strings->pr($_GET["dateDD"]) != '')
			$dateDD = $api->Strings->pr($_GET["dateDD"]);

		if ($dateSS != '' && $dateDD == '')
			$sql_get = " AND (`date_issue` >= '".$dateSS." 00:00:00')";
		else if ($dateSS == '' && $dateDD != '')
			$sql_get = " AND (`date_issue` <= '".$dateDD." 23:59:59')";
		else if ($dateSS != '' && $dateDD != '')
			$sql_get = " AND (`date_issue` >= '".$dateSS." 00:00:00' AND `date_issue` <= '".$dateDD." 23:59:59')";

		if (isset($_GET["search"]) && $_GET["search"] != '')
			$sql_get .= " AND (INSTR(`user_name`, '".$api->Strings->pr($_GET["search"])."') OR INSTR(`user_phone`, '".$api->Strings->pr($_GET["search"])."') OR INSTR(`mark`, '".$api->Strings->pr($_GET["search"])."') OR INSTR(`com_name`, '".$api->Strings->pr($_GET["search"])."') OR INSTR(`year`, '".$api->Strings->pr($_GET["search"])."') OR INSTR(`volume`, '".$api->Strings->pr($_GET["search"])."') OR INSTR(`vin`, '".$api->Strings->pr($_GET["search"])."') OR INSTR(`car_type`, '".$api->Strings->pr($_GET["search"])."') OR INSTR(`svh`, '".$api->Strings->pr($_GET["search"])."') OR INSTR(`broker_name`, '".$api->Strings->pr($_GET["search"])."'))";
		
		if (isset($_GET["status"]) && (intval($_GET["status"]) >= 1 && intval($_GET["status"]) <= 4))
			$sql_get .= " AND `status`=".intval($_GET["status"]);
		else if (
			(isset($_GET["status"]) && intval($_GET["status"]) == 10) &&
			(
				$api->Managers->man_block == 1 || // админ 
				$api->Managers->man_block == 5 // глав менеджер
			)
		)
			$sql_get .= " AND `status`=0";
		
		$users = Array(); $exams = Array(); $brokers = Array();
		$sU = mysql_query("SELECT * FROM `i_manager_users`");
		if (mysql_num_rows($sU) > 0)
		{	
			while($rU=mysql_fetch_array($sU))
			{
				$users[$rU["id"]]["name"] = stripslashes($rU["name"]);	
				
				if ($rU["id_section"] == 3)
					$exams[$rU["id"]] = stripslashes($rU["name"]);
				else if ($rU["id_section"] == 4)
					$brokers[$rU["id"]] = stripslashes($rU["name"]);
			}
		}	
		
		?>
		<style>

			body {
				background-color: #1f222e; /* Цвет фона */
			}

			.card {
				background-color: #021b3b;; 
			}

			.card-body {
				background-color: #021b3b;; 
			}

			
			.btn {
				background-color: white;;
			}

			
			.form-control {
				background-color: #a1a398; 
			}
			/* .container {
				background-color: black;
			} */
		</style>
		<!-- <div class = "container"> -->
			<div class="card">
				<div class="card-body" style="padding:10px 1.25rem 5px">
					<style>
						#search { display:inline-block; width:88%; }
						#status { margin-bottom: 5px; }
						html {
							background-color: black;
						}
						.sbros.btn-lg { padding: 0; margin: 5px 0 5px; font-size: 20px; }
						.dateS .dateInput { width:90px; }				
						.form-control-sm { padding:.4rem .4rem !important }
					</style>
					<script type="text/javascript">
						
						function getSearchD() {
							setTimeout(function() { self.location = "/order/?dateSS="+jQuery("#dateSS").val()+"&dateDD="+jQuery("#dateDD").val()<?=(isset($_GET["status"]) ? '+"&status='.$_GET["status"].'"' : '').(isset($_GET["exam"]) ? '+"&exam='.$_GET["exam"].'"' : '').(isset($_GET["broker"]) ? '+"&broker='.$_GET["broker"].'"' : '').(isset($_GET["search"]) ? '+"&search='.$_GET["search"].'"' : '')?>; }, 50);
						}

						function funcSearchD(event){
							if(event.keyCode==13){
								getSearchD();
							}
						}

						function getSearch() {
							setTimeout(function() { self.location = "/order/?search="+jQuery("#search").val()<?=(isset($_GET["status"]) ? '+"&status='.$_GET["status"].'"' : '').(isset($_GET["exam"]) ? '+"&exam='.$_GET["exam"].'"' : '').(isset($_GET["broker"]) ? '+"&broker='.$_GET["broker"].'"' : '').(isset($_GET["dateSS"]) ? '+"&dateSS='.$_GET["dateSS"].'"' : '').(isset($_GET["dateDD"]) ? '+"&dateDD='.$_GET["dateDD"].'"' : '')?>; }, 50);
						}

						function funcSearch(event){
							if(event.keyCode==13){
								getSearch();
							}
						}
						
						function chooseStatus() {
							if (jQuery("#status").val()!='')
								setTimeout(function() { self.location = "<?=$php_self.$get_status.($get_status!='' && $get_status!='?' ? '&' : '')?>status="+jQuery("#status").val(); }, 50);
							else
								setTimeout(function() { self.location = "<?=$php_self.($get_status!='?' ? $get_status : '')?>"; }, 50);
						}	
						
						function chooseExam() {
							if (jQuery("#exam").val() != '-1' && jQuery("#exam").val() != '')
								setTimeout(function() { self.location = "<?=$php_self.$get_exam.($get_exam!='' && $get_exam!='?' ? '&' : '')?>exam="+jQuery("#exam").val(); }, 50);
							else
								setTimeout(function() { self.location = "<?=$php_self.($get_exam!='?' ? $get_exam : '')?>"; }, 50);
						}	
						
						function chooseBroker() {
							if (jQuery("#broker").val() != '-1' && jQuery("#broker").val() != '')
								setTimeout(function() { self.location = "<?=$php_self.$get_broker.($get_broker!='' && $get_broker!='?' ? '&' : '')?>broker="+jQuery("#broker").val(); }, 50);
							else
								setTimeout(function() { self.location = "<?=$php_self.($get_broker!='?' ? $get_broker : '')?>"; }, 50);
						}	
						
						<? if ($api->Managers->man_block == 1 || $api->Managers->man_block == 2 || $api->Managers->man_block == 5) { ?>
						function get_ExcelOder()
						{															
							jQuery.ajax(
							{
								url: "ajax.php",
								data: "do=get_ExcelOder&x=secure<?=(isset($_GET["status"]) ? '&status='.$_GET["status"] : '').(isset($_GET["exam"]) ? '&exam='.$_GET["exam"] : '').(isset($_GET["broker"]) ? '&broker='.$_GET["broker"] : '').(isset($_GET["dateSS"]) ? '&dateSS='.$_GET["dateSS"] : '').(isset($_GET["dateDD"]) ? '&dateDD='.$_GET["dateDD"] : '')?>",
								type: "POST",
								dataType : "html",
								cache: false,

								beforeSend: function()		{ jQuery("#get_ExcelOder").hide(); jQuery("#load_ExcelOder").show(); },
								success:  function(data)  	{ jQuery("#protocol_ExcelOder").html(data); jQuery("#get_ExcelOder").show(); jQuery("#load_ExcelOder").hide(); },
								error: function()         	{ jQuery("#protocol_ExcelOder").html("<p style='color:#f00'>Невозможно связаться с сервером!</p>"); jQuery("#get_ExcelOder").show(); jQuery("#load_ExcelOder").hide(); }
							});																						
						}
						<? } ?>

					</script>
					<div class="row">
						<div class="mb-1 col-sm-12 col-md-2 mt-1">
							<select id="status" class="form-control form-control-sm" onchange="chooseStatus()" style="margin:3px 0 0">
								<option value=""> все статусы </option>							
									<? if ($api->Managers->man_block == 1 || $api->Managers->man_block == 5) { ?>
								<option value="10"<?=(isset($_GET["status"]) && intval($_GET["status"])==10 ? ' selected="selected"' : '')?>> на одобрении </option>
									<? } ?>
								<option value="1"<?=(isset($_GET["status"]) && intval($_GET["status"])==1 ? ' selected="selected"' : '')?>> новая </option>
								<option value="2"<?=(isset($_GET["status"]) && intval($_GET["status"])==2 ? ' selected="selected"' : '')?>> в работе </option>
								<option value="3"<?=(isset($_GET["status"]) && intval($_GET["status"])==3 ? ' selected="selected"' : '')?>> готова </option>
								<? if  (
									$api->Managers->man_block == 1 || // админ 
									$api->Managers->man_block == 2 || $api->Managers->man_block == 5 // менеджеры								
								) { ?>
								<option value="4"<?=(isset($_GET["status"]) && intval($_GET["status"])==4 ? ' selected="selected"' : '')?>> изменено досмотрщиком </option>
								<? } ?>
							</select>
						</div>
							<? if ($api->Managers->man_block == 1 || $api->Managers->man_block == 2 || $api->Managers->man_block == 5) { ?>
						<?php /*?><div class="mb-1 col-sm-12 col-md-2 mt-1">
							<select id="exam" class="form-control form-control-sm" onchange="chooseExam()" style="margin:3px 0 0">
								<option value="-1"> досмотрщики </option>															
								<option value="1"<?=(isset($_GET["exam"]) && intval($_GET["exam"])==1 ? ' selected="selected"' : '')?>> все взятые </option>
								<option value="0"<?=(isset($_GET["exam"]) && intval($_GET["exam"])==0 ? ' selected="selected"' : '')?>> без досмотрщика </option>
								<? foreach($exams as $k=>$v) { ?>
								<option value="<?=$k?>"<?=(isset($_GET["exam"]) && intval($_GET["exam"])==$k ? ' selected="selected"' : '')?>> <?=$v?> </option>
								<? }?>
							</select>
						</div><?php */?>
						<div class="mb-1 col-sm-12 col-md-2 mt-1">
							<select id="broker" class="form-control form-control-sm" onchange="chooseBroker()" style="margin:3px 0 0">
								<option value=""> все компании </option>															
								<? foreach($brokers as $k=>$v) { ?>
								<option value="<?=$k?>"<?=(isset($_GET["broker"]) && intval($_GET["broker"])==$k ? ' selected="selected"' : '')?>> <?=$v?> </option>
								<? }?>
							</select>
						</div>
							<? } ?>
						<div class="mb-1 col-sm-12 col-md-3 mt-1">
							<div class="dateS">
								<span class="no-mobile">с</span>
								<input type="text" onKeyDown="funcSearch(event);" placeholder="дата выпуска" value="<?=$dateSS?>" id="dateSS" class="form-control form-control-sm dateInput" />
								<span class="no-mobile">до</span>
								<input type="text" onKeyDown="funcSearchD(event);" placeholder="дата выпуска" value="<?=$dateDD?>" id="dateDD" class="form-control form-control-sm dateInput" />
								<a class="btn btn-link search_bt" onclick="getSearchD()"><i class="fas fa-search"></i></a>
							</div>
						</div>												
						<div class="mb-1 col-sm-12 col-md-3 mt-1">
							<input type="text" onKeyDown="funcSearch(event);" placeholder="поиск по машине..." value="<?=$api->Strings->pr(isset($_GET["search"]))?>" id="search" class="form-control form-control-sm" />
							<a class="btn btn-link search_bt" onclick="getSearch()"><i class="fas fa-search"></i></a>
						</div>	
							<? if (isset($_GET["status"]) || isset($_GET["exam"]) || isset($_GET["broker"]) || isset($_GET["search"]) || isset($_GET["dateSS"]) || isset($_GET["dateDD"])) { ?>
						<div class="mb-1 col-sm-12 col-md-1 mt-1">		
							<a data-toggle="tooltip" title="" class="btn btn-link btn-danger btn-lg sbros" data-original-title="Сбросить фильтры" href="<?=$php_self?>">
								<i class="fas fa-recycle"></i>
							</a>						
						</div>	
							<? } ?>
					</div>
				</div>
			</div>

			<div class="card">
				<div class="card-body" style="position:relative" id="width_body">				
					<script type="text/javascript">
						
						jQuery(document).ready(function(){
							jQuery(window).scroll(function() {
								var top = jQuery(document).scrollTop();
								if (top > 190) 
								{ 
									var width_scroll = document.querySelector('#width_body').offsetWidth - 40;
									jQuery('.fixed_scroll').addClass("active_scroll").css("width", width_scroll+"px"); 
								}
								else		   { jQuery('.fixed_scroll').removeClass("active_scroll"); }
							});
						});
						
					</script>
					<style>
						.table-responsive_up_scroll { height: 20px; overflow-x: scroll; overflow-y:hidden; }
						.up_scroll { height: 20px; }
						.fixed_scroll.active_scroll { position:fixed; height:20px; width:100%; top:0px; z-index:999; left:auto}
						.fixed_scroll.active_scroll .container_add { margin:0 auto; }					
					</style>
					<div class="fixed_scroll">
						<div class="container_add">
							<div class="table-responsive_up_scroll"><div class="up_scroll"></div></div>
						</div>
					</div>
					<div class="table-responsive">
						<div id="basic-datatables_wrapper" class="dataTables_wrapper dt-bootstrap4">
						<?
						$sql_wh = "";		
						
						if ($api->Managers->man_block == 1 || $api->Managers->man_block == 2 || $api->Managers->man_block == 5)
						{
							// выбор досмотрщика
							if (isset($_GET["exam"]) && intval($_GET["exam"])==1)
								$sql_wh = " AND (`id_exam` > 0 AND `id_exam` IS NOT NULL)";
							else if (isset($_GET["exam"]) && intval($_GET["exam"])==0)
								$sql_wh = " AND (`id_exam`=0 OR `id_exam` IS NULL)";
							else if (isset($_GET["exam"]) && intval($_GET["exam"]) > 1)
								$sql_wh = " AND `id_exam`='".intval($_GET["exam"])."'";
							
							if (isset($_GET["broker"]) && intval($_GET["broker"]) > 0)
								$sql_wh .= " AND `id_broker`='".intval($_GET["broker"])."'";
						}
			
						if ($api->Managers->man_block == 2)					
							$sql_wh .= " AND (`status`=1 OR ( (`status`=2 OR `status`=3 OR `status`=4) AND `id_man`='".$api->Managers->man_id."') )";											
						else if ($api->Managers->man_block == 3)
							//$sql_wh = " AND ( ( (`status`=1 OR `status`=2) AND (`id_exam`='".$api->Managers->man_id."' OR `id_exam`=0 OR `id_exam` IS NULL) ) OR ( (`status`=3 OR `status`=4) AND `id_exam`='".$api->Managers->man_id."') )";
							$sql_wh = " AND `id_exam`='".$api->Managers->man_id."'";
						else if ($api->Managers->man_block == 4)
							$sql_wh = " AND `id_broker`='".$api->Managers->man_id."'";
			
						$order_name = '';
						$by_name = '';
						$order_by = "`create_date` DESC, `id` DESC";
			
						if (isset($_GET["dateSS"]) || isset($_GET["dateDD"]))
							$order_by = "`date_issue` ASC, `create_date` DESC, `id` DESC";

						$i=1;
						$per_page = 100;
						$sql_ = "FROM `i_order` WHERE `user_name` IS NOT NULL".$sql_get.$sql_wh;
						$api->Pag->setvars($lang, $_SERVER['PHP_SELF'], $_SERVER['QUERY_STRING'], mysql_result(mysql_query("SELECT COUNT('id') ".$sql_), 0), $per_page, @$_GET['p']);
						if (!empty($_GET['p'])) {$start=$_GET['p'];} else {$start=1; $_GET["p"]=1;}
						$sql_query = "SELECT * ".$sql_." ORDER BY ".$order_by." LIMIT ".$api->Pag->start_from.", ".$per_page."";
			
						$s=mysql_query($sql_query);
						if (mysql_num_rows($s) > 0)
						{
							$koll = mysql_num_rows(mysql_query("SELECT * ".$sql_));
																														
							?>
							<table id="basic-datatables" class="display table table-striped table-hover dataTable">
								<thead>
									<tr>									
										<th>ID</th>
										<th nowrap>Дата выпуска</th>
										<th>Статус</th>
										<? 
										if (
											$api->Managers->man_block == 1 || // админ 
											$api->Managers->man_block == 2 || $api->Managers->man_block == 5 // менеджеры
										) { ?>
										<th nowrap>Статус №2</th>
										<th>Компания</th>
										<th>Брокер</th>
										<th>Менеджер</th>
										<? } ?>									
										<th>Марка</th>
										<th>VIN</th>
										<th>СОС</th>
										<? 
										if (
											$api->Managers->man_block == 1 || // админ 
											$api->Managers->man_block == 2 || $api->Managers->man_block == 5 // менеджеры
										) { ?>
										<th></th>
										<? } ?>
										<th>Дата создания</th>
										<? 
										if (
											$api->Managers->man_block == 1 || // админ 
											$api->Managers->man_block == 2 || $api->Managers->man_block == 5 || // менеджеры
											$api->Managers->man_block == 4 || // брокер
											$api->Managers->man_block == 3 // досмотрщик
										) { ?>									
										<th>ФИО</th>									
										<th>Телефон</th>									
										<? } ?>
										<? 
										if (
											$api->Managers->man_block == 3 // досмотрщик
										) { ?>									
										<th>СВХ</th>																		
										<? } ?>
									</tr>
								</thead>
							<?
							while($r=mysql_fetch_array($s))
							{							
								$link = '';							
								$link = ' style="cursor:pointer;" onclick="location.href=\'more.php?edit='.$r["id"].'\'"';
								
								$date = $api->Strings->date($lang,$r["create_date"],'sql','datetime');
								$date_issue = $api->Strings->date($lang,$r["date_issue"],'sql','date');
								
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
										$status = 'изменено'; $class_st = 'change';
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
															
								$man_name = $users[$r["id_man"]]["name"];
								$exam_name = $users[$r["id_exam"]]["name"];
								$company_name = $users[$r["id_broker"]]["name"];
								$broker_name = stripslashes($r["broker_name"]);
								
								/*
								$class_exam = '';	
								if (
									($exam_name!='') &&
									(
										$api->Managers->man_block == 1 || // админ 
										$api->Managers->man_block == 2 || $api->Managers->man_block == 5 || // менеджеры
										$api->Managers->man_block == 3 // досмотрщик
									)
								)
								{																
									$class_exam = ' class="red_td" title="не всё загружено"';								
									$sF = mysql_query("SELECT * FROM `i_foto` WHERE `id_order`='".$r["id"]."' LIMIT 1");
									if (mysql_num_rows($sF) > 0)
									{
										$rF = mysql_fetch_array($sF);

										$no_foto = '';
										for($i=1; $i<=12; $i++)
										{
											$dirFile = $_SERVER['DOCUMENT_ROOT'].'/upload/foto/'.$rF["foto_".$i];							

											if ($rF["foto_".$i]=='' || !is_file($dirFile))
												$no_foto .= ($no_foto!='' ? ', ' : '').$i;

										}

										if ($no_foto == '' && $rF["link_video"] != '')									
											$class_exam = ' class="green_td" title="все загружено"';								
									}
								}
								*/
								
								$uvesos = (intval($r["uvesos"]) == 1 ? '<i class="fas fa-check-square" style="color:#007bff"></i>' : '<i class="fas fa-ban"></i>');
								
								?>
								<tr role="row" class="<?=(($i%2)==1 ? 'odd' : 'even')?>">																
									<td<?=$link?> nowrap="nowrap"<?=($api->Managers->man_block == 3 ? $class_exam : '')?>><?=$r["id"]?></td>
									<td<?=$link?> nowrap="nowrap"><?=$date_issue?></td>
									<td<?=$link?> nowrap="nowrap" class="<?=$class_st?>"><?=$status?></td>
									<? 
									if (
										$api->Managers->man_block == 1 || // админ 
										$api->Managers->man_block == 2 || $api->Managers->man_block == 5 // менеджеры
									) { ?>
									<td<?=$link?> nowrap="nowrap" class="<?=$class_st2?>"><?=$status2?></td>
									<td<?=$link?> nowrap="nowrap"><?=$company_name?></td>	
									<td<?=$link?> nowrap="nowrap"><?=$broker_name?></td>
									<?php /*?><td<?=$link?> nowrap="nowrap"<?=$class_exam?>><?=$exam_name?></td><?php */?>
									<td<?=$link?> nowrap="nowrap"><?=$man_name?></td>
									<? } ?>								
									<td<?=$link?> nowrap="nowrap"><?=$r["mark"]?>, <?=$r["com_name"]?>, <?=$r["year"]?>, <?=$r["volume"]?></td>
									<td<?=$link?> nowrap="nowrap"><?=stripslashes($r["vin"])?></td>
									<td<?=$link?> nowrap="nowrap"><?=$uvesos?></td>
									<? 
									if (
										$api->Managers->man_block == 1 || // админ 
										$api->Managers->man_block == 2 || $api->Managers->man_block == 5 // менеджеры
									) { ?>
									<td nowrap="nowrap">
										<div class="form-button-action"> 
											<a data-toggle="tooltip" title="" class="btn btn-link btn-success btn-lg" data-original-title="Подробнее" href="more.php?edit=<?=$r["id"]?>">
												<i class="far fa-address-card"></i>
											</a>
											<? 
											if (
												($api->Managers->man_block == 1 || $api->Managers->man_block == 5) ||
												($api->Managers->man_block == 2 && (intval($r["status"]) == 2 || intval($r["status"]) == 4))
											) { ?>
											&nbsp;
											<a data-toggle="tooltip" title="" class="btn btn-link btn-danger btn-lg" data-original-title="Изменить" href="add.php?edit=<?=$r["id"]?>">
												<i class="far fa-edit"></i>
											</a>
											<? } ?>
										</div>
									</td>
									<? } ?>
									<td<?=$link?> nowrap="nowrap"><?=$date?></td>
									<? 
									if (
										$api->Managers->man_block == 1 || // админ 
										$api->Managers->man_block == 2 || $api->Managers->man_block == 5 || // менеджеры
										$api->Managers->man_block == 4 || // брокер
										$api->Managers->man_block == 3 // досмотрщик
									) { ?>
									<td<?=$link?> nowrap="nowrap"><?=stripslashes($r["user_name"])?></td>
									<td<?=$link?> nowrap="nowrap"><?=stripslashes($r["user_phone"])?></td>								
									<? } ?>
									<? 
									if (
										$api->Managers->man_block == 3 // досмотрщик
									) { ?>									
									<td><?=stripslashes($r["svh"])?></td>																		
									<? } ?>
								</tr>
								<?
								$i++;
							}
							?>
							</table>
							<?
						}
						?>
						</div>
						<?
						if (isset($koll) > $per_page)
							echo '<div class="mt-2">'.$api->Pag->pages_gen().'</div>';
						?>
					</div>
				</div>
			</div>
		// <div class="container">
		<div id="protocolSort"></div>		
		<?
	}
	else
		require($_SERVER["DOCUMENT_ROOT"]."/text_noAcces.php");
}
else
	require($_SERVER["DOCUMENT_ROOT"]."/text_noAuth.php");
require($_SERVER["DOCUMENT_ROOT"]."/libs/footer.php");?>
