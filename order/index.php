<?
$lang="ru";
$title="Задачи";
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
		$get_type = '?'.preg_replace('#(^|&)task_type=[0-9]+(|$)#', '', $_SERVER['QUERY_STRING']);
		$get_exam = '?'.preg_replace('#(^|&)exam=[0-9]+(|$)#', '', $_SERVER['QUERY_STRING']);
		$get_broker = '?'.preg_replace('#(^|&)level=[0-9]+(|$)#', '', $_SERVER['QUERY_STRING']);
		
		$sql_get = "";
		
		$dateSS = '';
		$dateDD = '';

		// if (isset($_GET["dateSS"]) && $api->Strings->pr($_GET["dateSS"]) != '')
		// 	$dateSS = $api->Strings->pr($_GET["dateSS"]);
		// if (isset($_GET["dateDD"]) && $api->Strings->pr($_GET["dateDD"]) != '')
		// 	$dateDD = $api->Strings->pr($_GET["dateDD"]);

		// if ($dateSS != '' && $dateDD == '')
		// 	$sql_get = " AND (`date_issue` >= '".$dateSS." 00:00:00')";
		// else if ($dateSS == '' && $dateDD != '')
		// 	$sql_get = " AND (`date_issue` <= '".$dateDD." 23:59:59')";
		// else if ($dateSS != '' && $dateDD != '')
		// 	$sql_get = " AND (`date_issue` >= '".$dateSS." 00:00:00' AND `date_issue` <= '".$dateDD." 23:59:59')";

		if (isset($_GET["search"]) && $_GET["search"] != '')
			$sql_get .= " AND (INSTR(`task_name`, '".$api->Strings->pr($_GET["search"]).")";
		
		if (isset($_GET["task_type"]) && (intval($_GET["task_type"]) >= 1 && intval($_GET["task_type"]) <= 4))
			$sql_get .= " AND `task_type`=".intval($_GET["task_type"]);
		else if (
			(isset($_GET["task_type"]) && intval($_GET["task_type"]) == 10) &&
			(
				$api->Managers->man_block == 1 || // админ 
				$api->Managers->man_block == 5 // глав менеджер
			)
		)
			$sql_get .= " AND `task_type`=0";
		
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

			/* .card-body {
				background-color: #021b3b;; 
			} */


			.btn {
				background-color: white;;
			}

            .card-body{
                color: white !important;
            }
			.form-control {
				background-color: #a1a398; 
			}
			/* .container {
				background-color: black;
			} */
		</style>
		<div class="card">
			<div class="card-body" style="padding:10px 1.25rem 5px">
				<style>
					#search { display:inline-block; width:88%; }
					#task_type { margin-bottom: 5px; }
					.sbros.btn-lg { padding: 0; margin: 5px 0 5px; font-size: 20px; }
					.dateS .dateInput { width:90px; }				
					.form-control-sm { padding:.4rem .4rem !important }
				</style>
				<script type="text/javascript">
					
					function getSearchD() {
						setTimeout(function() { self.location = "/order/?dateSS="+jQuery("#dateSS").val()+"&dateDD="+jQuery("#dateDD").val()<?=(isset($_GET["task_type"]) ? '+"&task_type='.$_GET["task_type"].'"' : '').(isset($_GET["exam"]) ? '+"&exam='.$_GET["exam"].'"' : '').(isset($_GET["broker"]) ? '+"&broker='.$_GET["broker"].'"' : '').(isset($_GET["search"]) ? '+"&search='.$_GET["search"].'"' : '')?>; }, 50);
					}

					function funcSearchD(event){
						if(event.keyCode==13){
							getSearchD();
						}
					}

					function getSearch() {
						setTimeout(function() { self.location = "/order/?search="+jQuery("#search").val()<?=(isset($_GET["task_type"]) ? '+"&task_type='.$_GET["task_type"].'"' : '').(isset($_GET["exam"]) ? '+"&exam='.$_GET["exam"].'"' : '').(isset($_GET["broker"]) ? '+"&broker='.$_GET["broker"].'"' : '').(isset($_GET["dateSS"]) ? '+"&dateSS='.$_GET["dateSS"].'"' : '').(isset($_GET["dateDD"]) ? '+"&dateDD='.$_GET["dateDD"].'"' : '')?>; }, 50);
					}

					function funcSearch(event){
						if(event.keyCode==13){
							getSearch();
						}
					}
					
					function chooseStatus() {
						if (jQuery("#task_type").val()!='')
							setTimeout(function() { self.location = "<?=$php_self.$get_type.($get_type!='' && $get_type!='?' ? '&' : '')?>task_type="+jQuery("#task_type").val(); }, 50);
						else
							setTimeout(function() { self.location = "<?=$php_self.($get_type!='?' ? $get_type : '')?>"; }, 50);
					}	
					
					function chooseExam() {
						if (jQuery("#exam").val() != '-1' && jQuery("#exam").val() != '')
							setTimeout(function() { self.location = "<?=$php_self.$get_exam.($get_exam!='' && $get_exam!='?' ? '&' : '')?>exam="+jQuery("#exam").val(); }, 50);
						else
							setTimeout(function() { self.location = "<?=$php_self.($get_exam!='?' ? $get_exam : '')?>"; }, 50);
					}	
					
					function chooseBroker() {
						if (jQuery("#level").val() != '-1' && jQuery("#level").val() != '')
							setTimeout(function() { self.location = "<?=$php_self.$get_broker.($get_broker!='' && $get_broker!='?' ? '&' : '')?>level="+jQuery("#level").val(); }, 50);
						else
							setTimeout(function() { self.location = "<?=$php_self.($get_broker!='?' ? $get_broker : '')?>"; }, 50);
					}	
					
					<? if ($api->Managers->man_block == 1 || $api->Managers->man_block == 2 || $api->Managers->man_block == 5) { ?>
					function get_ExcelOder()
					{															
						jQuery.ajax(
						{
							url: "ajax.php",
							data: "do=get_ExcelOder&x=secure<?=(isset($_GET["task_type"]) ? '&task_type='.$_GET["task_type"] : '').(isset($_GET["exam"]) ? '&exam='.$_GET["exam"] : '').(isset($_GET["level"]) ? '&level='.$_GET["level"] : '').(isset($_GET["dateSS"]) ? '&dateSS='.$_GET["dateSS"] : '').(isset($_GET["dateDD"]) ? '&dateDD='.$_GET["dateDD"] : '')?>",
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
						<select id="task_type" class="form-control form-control-sm" onchange="chooseStatus()" style="margin:3px 0 0">
							<option value=""> все категории </option>							
								// <? if ($api->Managers->man_block == 1 || $api->Managers->man_block == 5) { ?>
							<option value="10"<?=(isset($_GET["task_type"]) && intval($_GET["task_type"])==10 ? ' selected="selected"' : '')?>> stegano </option>
								// <? } ?>
							<option value="1"<?=(isset($_GET["task_type"]) && intval($_GET["task_type"])==1 ? ' selected="selected"' : '')?>> web </option>
							<option value="2"<?=(isset($_GET["task_type"]) && intval($_GET["task_type"])==2 ? ' selected="selected"' : '')?>> crypto </option>
							<option value="3"<?=(isset($_GET["task_type"]) && intval($_GET["task_type"])==3 ? ' selected="selected"' : '')?>> прочее </option>
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
						<select id="level" class="form-control form-control-sm" onchange="chooseBroker()" style="margin:3px 0 0">
							<option value=""> все сложности </option>															
							<option value="1"<?=(isset($_GET["level"]) && intval($_GET["level"])==1 ? ' selected="selected"' : '')?>> easy </option>
							<option value="2"<?=(isset($_GET["level"]) && intval($_GET["level"])==2 ? ' selected="selected"' : '')?>> medium </option>
							<option value="3"<?=(isset($_GET["level"]) && intval($_GET["level"])==3 ? ' selected="selected"' : '')?>> hard </option>
						</select>
					</div>
						<? } ?>											
					<div class="mb-1 col-sm-12 col-md-3 mt-1">
						<input type="text" onKeyDown="funcSearch(event);" placeholder="поиск по названию задачи..." value="<?=$api->Strings->pr(isset($_GET["search"]))?>" id="search" class="form-control form-control-sm" />
						<a class="btn btn-link search_bt" onclick="getSearch()"><i class="fas fa-search"></i></a>
					</div>	
						<? if (isset($_GET["task_type"]) || isset($_GET["exam"]) || isset($_GET["broker"]) || isset($_GET["search"]) || isset($_GET["dateSS"]) || isset($_GET["dateDD"])) { ?>
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
						$sql_wh .= " AND (`task_type`=1 OR ( (`task_type`=2 OR `task_type`=3 OR `task_type`=4) AND `id_man`='".$api->Managers->man_id."') )";											
					else if ($api->Managers->man_block == 3)
						//$sql_wh = " AND ( ( (`task_type`=1 OR `task_type`=2) AND (`id_exam`='".$api->Managers->man_id."' OR `id_exam`=0 OR `id_exam` IS NULL) ) OR ( (`task_type`=3 OR `task_type`=4) AND `id_exam`='".$api->Managers->man_id."') )";
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
					$sql_ = "FROM `i_order` WHERE `id` IS NOT NULL".$sql_get.$sql_wh;
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
									<th nowrap>Дата создания</th>
									<th>Название</th>
									<th>Категория</th>																									
									<th>Балл</th>
									<th>Сложность</th>
								</tr>
							</thead>
						<?
						while($r=mysql_fetch_array($s))
						{							
							$link = '';							
							$link = ' style="cursor:pointer;" onclick="location.href=\'more.php?edit='.$r["id"].'\'"';
							$task_name = $r["task_name"];
							$date = $api->Strings->date($lang,$r["create_date"],'sql','datetime');
							$points = intval($r["points"]);
							$level = $r["level"];
							// $date_issue = $api->Strings->date($lang,$r["date_issue"],'sql','date');
							
							$task_type = ''; $class_st = '';
							switch ($r["task_type"]) {
								case 'crypto':
									$task_type = 'crypto'; $class_st = 'appr';
									break;
								case 'stegano':
									$task_type = 'stegano'; $class_st = 'new';
									break;
								case 'web':
									$task_type = 'web'; $class_st = 'work';
									break;
								case 'прочее':
									$task_type = 'прочее'; $class_st = 'work';
									break;
							}
							
							// $status2 = ''; $class_st2 = '';
							// switch (intval($r["status2"])) {
							// 	case 1:
							// 		$status2 = 'новая'; $class_st2 = 'new';
							// 		break;
							// 	case 3:
							// 		$status2 = 'готова'; $class_st2 = 'done';
							// 		break;								
							// }
														
							// $man_name = isset($users[$r["id_man"]]["name"]);
							// $exam_name = isset($users[$r["id_exam"]]["name"]);
							// $company_name = isset($users[$r["id_broker"]]["name"]);
							// $broker_name = stripslashes($r["broker_name"]);
							
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
							
							// $uvesos = (intval($r["uvesos"]) == 1 ? '<i class="fas fa-check-square" style="color:#007bff"></i>' : '<i class="fas fa-ban"></i>');
							
							?>
							<tr role="row" class="<?=(($i%2)==1 ? 'odd' : 'even')?>">																
								<td<?=$link?> nowrap="nowrap"<?=($api->Managers->man_block == 3 ? $class_exam : '')?>><?=$r["id"]?></td>
								<td<?=$link?> nowrap="nowrap"><?=$date?></td>
								<td<?=$link?> nowrap="nowrap"><?=$task_name?></td>	
								<td<?=$link?> nowrap="nowrap" class="<?=$class_st?>"><?=$task_type?></td>
								<td<?=$link?> nowrap="nowrap"><?=$points?></td>
								<td<?=$link?> nowrap="nowrap"><?=$level?></td>
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
		<div id="protocolSort"></div>		
		<?
	}
	else
		require($_SERVER["DOCUMENT_ROOT"]."/text_noAcces.php");
}
else
	require($_SERVER["DOCUMENT_ROOT"]."/text_noAuth.php");
require($_SERVER["DOCUMENT_ROOT"]."/libs/footer.php");?>
