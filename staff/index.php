<?
$lang="ru";
$title="Пользователи";
$keywords="";
$description="";
require($_SERVER["DOCUMENT_ROOT"]."/libs/header.php");
if ($api->Managers->check_auth() == true)
{
	if ($api->Managers->man_block == 1)
	{
		?>
        <style>
            
           body {
                background-color: #0d1b2a; /* Цвет фона */
            }
            .card {
                background-color: #1a2035; /* Цвет фона */
            }
            .card-body{
                color: white !important;
            }
        </style>
		<div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <div id="basic-datatables_wrapper" class="dataTables_wrapper dt-bootstrap4">
					<?	
                    $s=mysql_query("SELECT * FROM `i_manager_users` WHERE `id`!=1 ORDER BY `id` ASC");
                    if (mysql_num_rows($s) > 0)
                    {
                        ?>
                        <table id="basic-datatables" class="display table table-striped table-hover dataTable">
        					<thead>
                                <tr>
                                    <th>Тип</th>
                                    <th>ФИ</th>
									<th>Телефон</th>
									<th>Логин</th>
                                    <!-- <th>Пароль</th> -->
                                    <th>Статус</th>
                                </tr>
							</thead>
                        <?
                        while($r=mysql_fetch_array($s))
                        {
							$type = '';
							if ($r["id_section"] == 1)			$type = 'Админ';
							else if ($r["id_section"] == 2)		$type = 'Преподователь';
							else if ($r["id_section"] == 3)		$type = 'Студент';
							// else if ($r["id_section"] == 4)		$type = 'Компания';
							// else if ($r["id_section"] == 5)		$type = 'Главный менеджер';
                            ?>
                            <tr role="row" class="<?=(($i%2)==1 ? 'odd' : 'even')?>" onclick="location.href='add.php?edit=<?=$r["id"]?>>'" style="cursor:pointer">
                                <td><?=$type?></td>
                                <td><?=$r["name"]?></td>
								<td><?=$r["phone"]?></td>
                                <td><?=$r["login"]?></td>
                                <!-- <td><?=$r["pass"]?></td> -->
                                <td><?=(intval($r["active"]) == 1 ? 'активный' : 'заблокированный')?></td>
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
		require($_SERVER["DOCUMENT_ROOT"]."/text_noAcces.php");
}
else
	require($_SERVER["DOCUMENT_ROOT"]."/text_noAuth.php");
require($_SERVER["DOCUMENT_ROOT"]."/libs/footer.php");?>
