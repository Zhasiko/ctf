<?
$lang="ru";
$title="Новые заявки";
$keywords="";
$description="";
require($_SERVER["DOCUMENT_ROOT"]."/libs/header.php");

if ($api->Managers->check_auth() == true)
{
	if (
		$api->Managers->man_block == 3 // досмотрщик		
	)
	{		
		$php_self = '/order/fresh.php';
		$get_status = '?'.preg_replace('#(^|&)status=[0-9]+(|$)#', '', $_SERVER['QUERY_STRING']);
		
		$sql_get = "";
		
		$dateSS = '';
		$dateDD = '';

		if (isset($_GET["dateSS"]) && $api->Strings->pr($_GET["dateSS"]) != '')
			$dateSS = $api->Strings->pr($_GET["dateSS"]);
		if (isset($_GET["dateDD"]) && $api->Strings->pr($_GET["dateDD"]) != '')
			$dateDD = $api->Strings->pr($_GET["dateDD"]);

		if ($dateSS != '' && $dateDD == '')
			$sql_get = " AND (`create_date` > '".$dateSS." 00:00:00')";
		else if ($dateSS == '' && $dateDD != '')
			$sql_get = " AND (`create_date` < '".$dateDD." 23:59:59')";
		else if ($dateSS != '' && $dateDD != '')
			$sql_get = " AND (`create_date` > '".$dateSS." 00:00:00' AND `create_date` < '".$dateDD." 23:59:59')";

		if (isset($_GET["search"]) && $_GET["search"] != '')
			$sql_get .= " AND (INSTR(`user_name`, '".$api->Strings->pr($_GET["search"])."') OR INSTR(`user_phone`, '".$api->Strings->pr($_GET["search"])."') OR INSTR(`mark`, '".$api->Strings->pr($_GET["search"])."') OR INSTR(`com_name`, '".$api->Strings->pr($_GET["search"])."') OR INSTR(`year`, '".$api->Strings->pr($_GET["search"])."') OR INSTR(`volume`, '".$api->Strings->pr($_GET["search"])."') OR INSTR(`vin`, '".$api->Strings->pr($_GET["search"])."') OR INSTR(`car_type`, '".$api->Strings->pr($_GET["search"])."') OR INSTR(`svh`, '".$api->Strings->pr($_GET["search"])."'))";				
		
		?>
		<div class="card">
			<div class="card-body" style="padding:10px 1.25rem 5px">
				<style>
					#search { display:inline-block; width:88%; }
					#status { margin-bottom: 5px; }
					.sbros.btn-lg { padding: 0; margin: 5px 0 5px; font-size: 20px; }
				</style>
				<style>
					.form-control-sm { padding:.4rem .5rem !important }
				</style>
				<script type="text/javascript">
					
					function getSearchD() {
						setTimeout(function() { self.location = "/order/?dateSS="+jQuery("#dateSS").val()+"&dateDD="+jQuery("#dateDD").val()<?=(isset($_GET["status"]) ? '+"&status='.$_GET["status"].'"' : '').(isset($_GET["search"]) ? '+"&search='.$_GET["search"].'"' : '')?>; }, 50);
					}

					function funcSearchD(event){
						if(event.keyCode==13){
							getSearchD();
						}
					}

					function getSearch() {
						setTimeout(function() { self.location = "/order/?search="+jQuery("#search").val()<?=(isset($_GET["status"]) ? '+"&status='.$_GET["status"].'"' : '').(isset($_GET["dateSS"]) ? '+"&dateSS='.$_GET["dateSS"].'"' : '').(isset($_GET["dateDD"]) ? '+"&dateDD='.$_GET["dateDD"].'"' : '')?>; }, 50);
					}

					function funcSearch(event){
						if(event.keyCode==13){
							getSearch();
						}
					}														

				</script>
				<div class="row">						
					<div class="mb-1 col-sm-12 col-md-4 mt-1">
						<div class="dateS">
							<span class="no-mobile">с &nbsp;</span>
							<input type="text" onKeyDown="funcSearch(event);" placeholder="дата" value="<?=$dateSS?>" id="dateSS" class="form-control form-control-sm dateInput" />
							-<span class="no-mobile"> &nbsp; до</span>
							<input type="text" onKeyDown="funcSearchD(event);" placeholder="дата" value="<?=$dateDD?>" id="dateDD" class="form-control form-control-sm dateInput" />
							<a class="btn btn-link search_bt" onclick="getSearchD()"><i class="fas fa-search"></i></a>
						</div>
					</div>												
					<div class="mb-1 col-sm-12 col-md-5 mt-1">
						<input type="text" onKeyDown="funcSearch(event);" placeholder="поиск по машине..." value="<?=$api->Strings->pr(isset($_GET["search"]))?>" id="search" class="form-control form-control-sm" />
						<a class="btn btn-link search_bt" onclick="getSearch()"><i class="fas fa-search"></i></a>
					</div>	
						<? if (isset($_GET["status"]) ||isset($_GET["search"]) || isset($_GET["dateSS"]) || isset($_GET["dateDD"])) { ?>
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
			<div class="card-body" style="position:relative">				
				<script type="text/javascript">
					
					jQuery(document).ready(function(){
						jQuery(window).scroll(function() {
							var top = jQuery(document).scrollTop();
							if (top > 100) { jQuery('.fixed_scroll').addClass("active_scroll"); }
							else		   { jQuery('.fixed_scroll').removeClass("active_scroll"); }
						});
					});
					
				</script>
				<div class="fixed_scroll">
					<div class="container_add">
						<div class="table-responsive_up_scroll"><div class="up_scroll"></div></div>
					</div>
				</div>
				<div class="table-responsive">
					<div id="basic-datatables_wrapper" class="dataTables_wrapper dt-bootstrap4">
                    <?
					$sql_wh = " AND (`id_exam`=0 OR `id_exam` IS NULL)";
					
					$order_name = '';
					$by_name = '';
					$order_by = "`create_date` DESC, `id` DESC";					

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
						
						$users = Array();
						$sU = mysql_query("SELECT * FROM `i_manager_users`");
						if (mysql_num_rows($sU) > 0)
						{	
							while($rU=mysql_fetch_array($sU))
							{
								$users[$rU["id"]]["name"] = stripslashes($rU["name"]);	
							}
						}												
						
						?>
						<table id="basic-datatables" class="display table table-striped table-hover dataTable">
							<thead>
								<tr>									
									<th>ID</th>
									<th>Дата</th>
									<th>Статус</th>
									<th>Марка</th>
									<th>VIN</th>
									<th>ФИО</th>									
									<th>Телефон</th>									
									<th>СВХ</th>	
								</tr>
							</thead>
						<?
						while($r=mysql_fetch_array($s))
						{							
							$link = '';							
							$link = ' style="cursor:pointer;" onclick="location.href=\'more.php?edit='.$r["id"].'\'"';
							
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
									$status = 'изменено'; $class_st = 'change';
									break;
							}
														
							$man_name = isset($users[$r["id_man"]]["name"]);
							$exam_name = isset($users[$r["id_exam"]]["name"]);
							$broker_name = isset($users[$r["id_broker"]]["name"]);
														
							?>
							<tr role="row" class="<?=(($i%2)==1 ? 'odd' : 'even')?>">																
								<td<?=$link?> nowrap="nowrap"<?=($api->Managers->man_block == 3 ? isset($class_exam ): '')?>><?=$r["id"]?></td>
								<td<?=$link?> nowrap="nowrap"><?=$date?></td>
								<td<?=$link?> nowrap="nowrap" class="<?=$class_st?>"><?=$status?></td>
								<td<?=$link?> nowrap="nowrap"><?=$r["mark"]?>, <?=$r["com_name"]?>, <?=$r["year"]?>, <?=$r["volume"]?></td>
								<td<?=$link?> nowrap="nowrap"><?=stripslashes($r["vin"])?></td>								
								<td<?=$link?> nowrap="nowrap"><?=stripslashes($r["user_name"])?></td>
								<td<?=$link?> nowrap="nowrap"><?=stripslashes($r["user_phone"])?></td>								
								<td><?=stripslashes($r["svh"])?></td>
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
					if ($koll > $per_page)
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
