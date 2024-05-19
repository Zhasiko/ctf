<?
$lang="ru";
$title="Список студентов";
$keywords="";
$description="";
require($_SERVER["DOCUMENT_ROOT"]."/libs/header.php");
if ($api->Managers->check_auth() == true)
{
	
		?>
        <style>
            
           body {
                
                background-color: #0d1b2a;
            }
            .card {
                background-color: #1a2035; /* Цвет фона */
                background: #12192c;
				background: -webkit-linear-gradient(to right, #1b2735, #12192c);
				background: linear-gradient(to right, #1b2735, #12192c);
            }
            .card-body{
                color: white !important;
            }
            .highlight-row {
                border: 1px solid white !important; 
                background-color: #2a3a5c !important; 
            }
        </style>
		<div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <div id="basic-datatables_wrapper" class="dataTables_wrapper dt-bootstrap4">
					<?	
                    $s=mysql_query("SELECT * FROM `i_manager_users` WHERE `id_section`=3 ORDER BY `points` DESC");
                    if (mysql_num_rows($s) > 0)
                    {
                        ?>
                        <table id="basic-datatables" class="display table table-striped table-hover dataTable">
        					<thead>
                                <tr>
                                    
                                    <th>ФИ</th>
                                    <th>Баллы</th>
                                    <th>Количество решенных задач</th>
                                </tr>
							</thead>
                        <?
                        while($r=mysql_fetch_array($s))
                        {
                            $rowClass = ($r["id"] == $api->Managers->man_id) ? 'highlight-row' : '';
                            ?>
                            <tr role="row" class="<?=(($i%2)==1 ? 'odd' : 'even')?> <?php echo $rowClass; ?>" style="height: 40px; @media screen and (max-width: 767px) { height: auto; }; ">

								<td><?=$r["name"]?></td>
                                <td><?=$r["points"]?></td>
                                <td><?=$r["task_amount"]?></td>
                            </tr>
                            <?
                        }
                        ?>
                        </table>
                        <?
					}
					?>
					</div>
                </div>
            </div>
        </div>
		<?
}
else
	require($_SERVER["DOCUMENT_ROOT"]."/text_noAuth.php");
require($_SERVER["DOCUMENT_ROOT"]."/libs/footer.php");?>