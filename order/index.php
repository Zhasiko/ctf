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
		$user_id = $api->Managers->man_id;
		$php_self = '/order/';
		$get_type = '?'.preg_replace('#(^|&)task_type=[0-9]+(|$)#', '', $_SERVER['QUERY_STRING']);
		$get_level = '?'.preg_replace('#(^|&)level=[0-9]+(|$)#', '', $_SERVER['QUERY_STRING']);
		$get_solved = '?'.preg_replace('#(^|&)solved=[0-9]+(|$)#', '', $_SERVER['QUERY_STRING']);
		
		$sql_get = "";
		$sql_solved = "";
		
		if (isset($_GET["search"]) && $_GET["search"] != '')
			$sql_get .= " AND (INSTR(`task_name`, '".$api->Strings->pr($_GET["search"])."'))";
		
		if (isset($_GET["task_type"])){	
			if($_GET["task_type"] != 'all'){
				$sql_get .= " AND `task_type`='".$_GET["task_type"]."'";
			}
		}	
		if (isset($_GET["solved"])){	
			if($_GET["solved"] != 'all'){
				$sub_query = "SELECT `task_id` FROM `i_solved` WHERE `user_id` = ".$api->Managers->man_id;
				if($_GET["solved"] == 'решенные'){
					$sql_solved .= " AND `id` IN (".$sub_query.")";
				}
				else if($_GET["solved"] == 'нерешенные'){
					$sql_solved .= " AND `id` NOT IN (".$sub_query.")";
				}
			}
		}	
		
		?>
		<style>

			body {
				background-color: #1f222e; /* Цвет фона */
			}

			.card {
				background-color: white;; 
			}

			.card-body {
				color: white;
				
				background-color: #021b3b;; 
			}


			.btn {
				background-color: white;;
			}


			.form-control {
				background-color: #a1a398; 
			}

			

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

					function getSearch() {
						setTimeout(function() { self.location = "/order/?search="+jQuery("#search").val()<?=(isset($_GET["task_type"]) ? '+"&task_type='.$_GET["task_type"].'"' : '').(isset($_GET["level"]) ? '+"&level='.$_GET["level"].'"' : '')?>; }, 50);
					}

					function funcSearch(event){
						if(event.keyCode==13){
							getSearch();
						}
					}
					
					function chooseType() {
						if (jQuery("#task_type").val()!='')
							setTimeout(function() { self.location = "<?=$php_self.$get_type.($get_type!='' && $get_type!='?' ? '&' : '')?>task_type="+jQuery("#task_type").val(); }, 50);
						else
							setTimeout(function() { self.location = "<?=$php_self.($get_type!='?' ? $get_type : '')?>"; }, 50);
					}
					
					
					function chooseLevel() {
						if (jQuery("#level").val() != '')
							setTimeout(function() { self.location = "<?=$php_self.$get_level.($get_level!='' && $get_level!='?' ? '&' : '')?>level="+jQuery("#level").val(); }, 50);
						else
							setTimeout(function() { self.location = "<?=$php_self.($get_level!='?' ? $get_level : '')?>"; }, 50);
					}	
					function chooseSolved() {
						if (jQuery("#solved").val() != '')
							setTimeout(function() { self.location = "<?=$php_self.$get_solved.($get_solved!='' && $get_solved!='?' ? '&' : '')?>solved="+jQuery("#solved").val(); }, 50);
						else
							setTimeout(function() { self.location = "<?=$php_self.($get_solved!='?' ? $get_solved : '')?>"; }, 50);
					}	
					

				</script>
				<div class="row">
					<div class="mb-1 col-sm-12 col-md-2 mt-1">
						<select id="task_type" class="form-control form-control-sm" onchange="chooseType()" style="margin:3px 0 0">
							<option value="all"> все категории </option>							
							<option value="stegano"<?=(isset($_GET["task_type"]) && $_GET["task_type"]=='stegano' ? ' selected="selected"' : '')?>> stegano </option>
							<option value="web"<?=(isset($_GET["task_type"]) && $_GET["task_type"]=='web' ? ' selected="selected"' : '')?>> web </option>
							<option value="crypto"<?=(isset($_GET["task_type"]) && $_GET["task_type"]=='crypto' ? ' selected="selected"' : '')?>> crypto </option>
							<option value="прочее"<?=(isset($_GET["task_type"]) && $_GET["task_type"]=='прочее' ? ' selected="selected"' : '')?>> прочее </option>
						</select>
					</div>
					<div class="mb-1 col-sm-12 col-md-2 mt-1">
						<select id="level" class="form-control form-control-sm" onchange="chooseLevel()" style="margin:3px 0 0">
							<option value="all"> все сложности </option>															
							<option value="easy"<?=(isset($_GET["level"]) && $_GET["level"]=='easy' ? ' selected="selected"' : '')?>> easy </option>
							<option value="medium"<?=(isset($_GET["level"]) && $_GET["level"]=='medium' ? ' selected="selected"' : '')?>> medium </option>
							<option value="hard"<?=(isset($_GET["level"]) && $_GET["level"]=='hard' ? ' selected="selected"' : '')?>> hard </option>
						</select>
					</div>
					<div class="mb-1 col-sm-12 col-md-2 mt-1">
						<select id="solved" class="form-control form-control-sm" onchange="chooseSolved()" style="margin:3px 0 0">
							<option value="all"> все задачи </option>															
							<option value="решенные"<?=(isset($_GET["solved"]) && $_GET["solved"]=='решенные' ? ' selected="selected"' : '')?>> решенные </option>
							<option value="нерешенные"<?=(isset($_GET["solved"]) && $_GET["solved"]=='нерешенные' ? ' selected="selected"' : '')?>> нерешенные </option>
						</select>
					</div>
					<div class="mb-1 col-sm-12 col-md-3 mt-1">
						<input type="text" onKeyDown="funcSearch(event);" placeholder="поиск по названию задачи..." value="<?=$api->Strings->pr(isset($_GET["search"]))?>" id="search" class="form-control form-control-sm" />
						<a class="btn btn-link search_bt" onclick="getSearch()"><i class="fas fa-search"></i></a>
					</div>	
						<? if (isset($_GET["task_type"]) || isset($_GET["level"]) || isset($_GET["search"]) || (isset($_GET["solved"]))) { ?>
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
				<div class="fixed_scroll">
					<div class="container_add">
						<div class="table-responsive_up_scroll"><div class="up_scroll"></div></div>
					</div>
				</div>
				<div class="table-responsive">
					<div id="basic-datatables_wrapper" class="dataTables_wrapper dt-bootstrap4">
                    <?
					$sql_wh = "";		
					
					if (isset($_GET["level"])){
						if($_GET["level"] != 'all'){
							$sql_wh .= " AND `level`='".$_GET["level"]."'";
						}
					}
		
					$order_by = "`create_date` DESC, `id` DESC";

					$i=1;
					$per_page = 100;
					$sql_ = "FROM `i_order` WHERE `id` IS NOT NULL".$sql_solved.$sql_get.$sql_wh;
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
									<?
										if($api->Managers->man_block == 3){ ?>
											<th>Статус</th>
										<? } ?>
									?>
								</tr>
							</thead>
						<?
						while($r=mysql_fetch_array($s))
						{							
							$link = '';							
							$link = ' style="cursor:pointer;" onclick="location.href=\'more.php?id='.$r["id"].'\'"';
							$task_name = $r["task_name"];
							$date = $api->Strings->date($lang,$r["create_date"],'sql','datetime');
							$points = intval($r["points"]);
							$level = $r["level"];
							
							$task_type = ''; $class_st = ''; $class_lvl = '';
							switch ($r["task_type"]) {
								case 'crypto':
									$task_type = 'crypto'; $class_st = 'new';
									break;
								case 'stegano':
									$task_type = 'stegano'; $class_st = 'appr';
									break;
								case 'web':
									$task_type = 'web'; $class_st = 'work';
									break;
								case 'прочее':
									$task_type = 'прочее'; $class_st = 'change';
									break;
							}
							
							?>
							<tr role="row" class="<?=(($i%2)==1 ? 'odd' : 'even')?>" style="height: 30px; @media screen and (max-width: 767px) { height: auto; }; ">																
								<td<?=$link?> nowrap="nowrap"><?=$r["id"]?></td>
								<td<?=$link?> nowrap="nowrap"><?=$date?></td>
								<td<?=$link?> nowrap="nowrap"><?=$task_name?></td>	
								<td<?=$link?> nowrap="nowrap" class="<?=$class_st?>"><?=$task_type?></td>
								<td<?=$link?> nowrap="nowrap"><?=$points?></td>
								<td<?=$link?> nowrap="nowrap"><?=$level?></td>
								<?
									if (($r["id"] != '') && ($user_id != '') && (mysql_num_rows(mysql_query("SELECT `id` FROM `i_solved` WHERE `task_id`='".$r["id"]."' AND `user_id`='".$user_id."' LIMIT 1")) == 1)){ ?>
										<td<?=$link?> nowrap="nowrap"><?="✅"?></td>
									<? }
								?>
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
