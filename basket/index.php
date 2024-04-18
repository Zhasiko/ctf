<?
$lang="ru";
$title="Корзина";
$keywords="";
$description="";
require($_SERVER["DOCUMENT_ROOT"]."/libs/header.php");

if ($api->Managers->check_auth() == true)
{
	if (
		$api->Managers->man_block == 1 || // админ 
		$api->Managers->man_block == 5 // менеджеры
	)
	{		
		?>
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
					$order_name = '';
					$by_name = '';
					$order_by = "`create_date` DESC, `id` DESC";					

					$i=1;
					$per_page = 100;
					$sql_ = "FROM `i_logs` WHERE `json_obj` IS NOT NULL";
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
									<th>Менеджер</th>
									<th>ID заказа</th>
									<th>ФИО</th>								
									<th>Машина</th>
								</tr>
							</thead>
						<?
						while($r=mysql_fetch_array($s))
						{							
							$link = '';							
							$link = ' style="cursor:pointer;" onclick="location.href=\'info.php?edit='.$r["id"].'\'"';
							
							$date = $api->Strings->date($lang,$r["create_date"],'sql','datetime');														
							$man_name = $users[$r["id_user"]]["name"];
							
							$json_obj = json_decode($r['json_obj'], true);
							$id_order = $json_obj["id"];
							$fio_order = $json_obj["user_name"];
							$car_order = $json_obj["mark"].', '.$json_obj["com_name"].', '.$json_obj["year"].', '.$json_obj["volume"];
							?>
							<tr role="row" class="<?=(($i%2)==1 ? 'odd' : 'even')?>">																
								<td<?=$link?> nowrap="nowrap"><?=$r["id"]?></td>
								<td<?=$link?> nowrap="nowrap"><?=$date?></td>								
								<td<?=$link?> nowrap="nowrap"><?=$man_name?></td>
								<td<?=$link?> nowrap="nowrap"><?=$id_order?></td>
								<td<?=$link?> nowrap="nowrap"><?=$fio_order?></td>
								<td<?=$link?> nowrap="nowrap"><?=$car_order?></td>
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
