<?
$lang="ru";
$title="База машин";
$keywords="";
$description="";
require($_SERVER["DOCUMENT_ROOT"]."/libs/header.php");

if ($api->Managers->check_auth() == true)
{
	if ($api->Managers->man_block == 1 || $api->Managers->man_block == 2 || $api->Managers->man_block == 5)
	{
		if (isset($_GET["f"]) && intval($_GET["f"]) > 0) {
		?>
		<script type="text/javascript">
			jQuery(document).ready(function(){
				jQuery('html, body').animate({ scrollTop: jQuery("#field_<?=intval($_GET["f"])?>").offset().top-73 }, 1);
			});
		</script>
		<? } ?>
		
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
										
					function getSearch() {
						setTimeout(function() { self.location = "/settings/baza/?search="+jQuery("#search").val(); }, 50);
					}

					function funcSearch(event){
						if(event.keyCode==13){
							getSearch();
						}
					}														

				</script>
				<div class="row">																
					<div class="mb-1 col-sm-12 col-md-5 mt-1">
						<input type="text" onKeyDown="funcSearch(event);" placeholder="поиск по машине..." value="<?=$api->Strings->pr($_GET["search"])?>" id="search" class="form-control form-control-sm" />
						<a class="btn btn-link search_bt" onclick="getSearch()"><i class="fas fa-search"></i></a>
					</div>	
						<? if ($_GET["search"]) { ?>
					<div class="mb-1 col-sm-12 col-md-1 mt-1">		
						<a data-toggle="tooltip" title="" class="btn btn-link btn-danger btn-lg sbros" data-original-title="Сбросить фильтры" href="/settings/baza/">
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
					$sql_get = "";
					if (isset($_GET["search"]) && $_GET["search"] != '')
						$sql_get .= " AND (INSTR(`mark`, '".$api->Strings->pr($_GET["search"])."') OR INSTR(`com_name`, '".$api->Strings->pr($_GET["search"])."') OR INSTR(`year`, '".$api->Strings->pr($_GET["search"])."') OR INSTR(`volume`, '".$api->Strings->pr($_GET["search"])."') OR INSTR(`car_type`, '".$api->Strings->pr($_GET["search"])."'))";
		
					$order_name = '';
					$by_name = '';
					$order_by = "`mark` ASC, `id` DESC";
					if (
						(isset($_GET["order"]) && ($_GET["order"] == 'car_type' || $_GET["order"] == 'mark' || $_GET["order"] == 'com_name' || $_GET["order"] == 'year' || $_GET["order"] == 'volume') ) &&
						(isset($_GET["by"]) && ($_GET["by"] == 'asc' || $_GET["by"] == 'desc') )
					)
					{
						$order_name = $api->Strings->pr($_GET["order"]);
						$by_name = $api->Strings->pr($_GET["by"]);

						$order_by = "`".$order_name."` ".$by_name;
					}

					$i=1;
					$per_page = 1000;
					$sql_ = "FROM `i_baza` WHERE `mark` IS NOT NULL".$sql_get;
					$api->Pag->setvars($lang, $_SERVER['PHP_SELF'], $_SERVER['QUERY_STRING'], mysql_result(mysql_query("SELECT COUNT('id') ".$sql_), 0), $per_page, @$_GET['p']);
					if (!empty($_GET['p'])) {$start=$_GET['p'];} else {$start=1; $_GET["p"]=1;}
					$sql_query = "SELECT * ".$sql_." ORDER BY ".$order_by." LIMIT ".$api->Pag->start_from.", ".$per_page."";
					$s=mysql_query($sql_query);
					if (mysql_num_rows($s) > 0)
					{
						$koll = mysql_num_rows(mysql_query("SELECT * ".$sql_));
						
						$filter_gets = '';
						if (isset($_GET["car_type"]) && $_GET["car_type"]!='')			$filter_gets .= '&car_type='.$_GET["car_type"];
						if (isset($_GET["mark"]) && $_GET["mark"]!='')					$filter_gets .= '&mark='.$_GET["mark"];
						if (isset($_GET["com_name"]) && $_GET["com_name"]!='')			$filter_gets .= '&com_name='.$_GET["com_name"];
						if (isset($_GET["year"]) && $_GET["year"]!='')					$filter_gets .= '&year='.$_GET["year"];
						if (isset($_GET["volume"]) && $_GET["volume"]!='')				$filter_gets .= '&volume='.$_GET["volume"];
						?>
						<table id="basic-datatables" class="display table table-striped table-hover dataTable">
							<thead>
								<tr>									
									<th class="sorting<?=($order_name == 'car_type' ? '_'.$by_name : '')?>" onclick="location.href='?order=car_type&by=<?=($by_name == 'asc' && $order_name == 'car_type' ? 'desc' : 'asc').$filter_gets?>'" nowrap style="padding-right:30px !important">Тип автомобиля</th>
									<th class="sorting<?=($order_name == 'mark' ? '_'.$by_name : '')?>" onclick="location.href='?order=mark&by=<?=($by_name == 'asc' && $order_name == 'mark' ? 'desc' : 'asc').$filter_gets?>'" nowrap style="padding-right:30px !important">Марки</th>
									<th nowrap style="padding-right:30px !important" class="sorting<?=($order_name == 'com_name' ? '_'.$by_name : '')?>" onclick="location.href='?order=com_name&by=<?=($by_name == 'asc' && $order_name == 'com_name' ? 'desc' : 'asc').$filter_gets?>'">Ком наименование</th>
									<th class="sorting<?=($order_name == 'year' ? '_'.$by_name : '')?>" onclick="location.href='?order=year&by=<?=($by_name == 'asc' && $order_name == 'year' ? 'desc' : 'asc').$filter_gets?>'" style="padding-right:30px !important">Год</th>
									<th class="sorting<?=($order_name == 'volume' ? '_'.$by_name : '')?>" onclick="location.href='?order=volume&by=<?=($by_name == 'asc' && $order_name == 'volume' ? 'desc' : 'asc').$filter_gets?>'" style="padding-right:30px !important">Объём</th>
									<th>&nbsp;<th>
								</tr>
							</thead>
						<?
						while($r=mysql_fetch_array($s))
						{							
							$link = '';
							
							$gets = '';
							foreach($_GET as $k_=>$v_)
								$gets .= '&'.$k_.'='.$v_;
								
							$link = ' style="cursor:pointer;" onclick="location.href=\'add.php?edit='.$r["id"].$gets.'\'"';
							
							?>
							<tr role="row" class="<?=(($i%2)==1 ? 'odd' : 'even')?>">
								<td<?=$link?> nowrap="nowrap"><?=stripslashes($r["car_type"])?></td>
								<td<?=$link?> nowrap="nowrap"><?=stripslashes($r["mark"])?></td>
								<td<?=$link?> nowrap="nowrap"><?=$api->Strings->pr_plus(htmlspecialchars_decode(stripslashes($r["com_name"])))?></td>
								<td<?=$link?> nowrap="nowrap"><?=stripslashes($r["year"])?></td>
								<td<?=$link?> nowrap="nowrap"><?=stripslashes($r["volume"])?></td>
								<td>
									<a data-toggle="tooltip" title="" class="btn btn-link btn-success btn-lg" data-original-title="сделать дубликат" href="add.php?copy=<?=$r["id"]?>">
										<i class="far fa-clone"></i>
									</a>
								</td>
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
