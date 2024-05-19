<?
$lang="ru";
$title="События";
$keywords="";
$description="";
require($_SERVER["DOCUMENT_ROOT"]."/libs/header.php");
if ($api->Managers->check_auth() == true && $api->Managers->man_block == 3)
{
	
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
        </style>
		<div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <div id="basic-datatables_wrapper" class="dataTables_wrapper dt-bootstrap4">
					<?	
                    $s=mysql_query("SELECT * FROM `i_events` WHERE `active` = 1 ORDER BY `id` ASC");
                    if (mysql_num_rows($s) > 0)
                    {
                        ?>
                        <table id="basic-datatables" class="display table table-striped table-hover dataTable">
        					<thead>
                                <tr>
                                    <th>Название</th>
									<th>Описание</th>
									<th>Дата</th>
                                    <th>Ссылка</th>
                                    <th>Статус</th>
                                    <!-- <th>Тип</th>
                                    <th>ФИ</th>
									<th>Телефон</th>
									<th>Логин</th>
                                    <th>Пароль</th> -->
                                    <!-- <th>Статус</th> -->
                                </tr>
							</thead>
                        <?
                        while($r=mysql_fetch_array($s))
                        {
                            ?>
                            <!-- style="cursor:pointer" -->
                            <tr role="row" class="<?=(($i%2)==1 ? 'odd' : 'even')?>" > 
                                <td><?=$r["name"]?></td>
								<td><?=$r["description"]?></td>
                                <td><?=$r["date"]?></td>
                                <td><?=$r["link"]?></td>
                                <td><?=(intval($r["active"]) == 1 ? 'Актуальный' : 'Неактуальный')?></td>
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
	
    else if ($api->Managers->check_auth() == true && ($api->Managers->man_block == 1 || $api->Managers->man_block == 2))
    { ?>
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
         </style>
         <div class="card">
             <div class="card-body">
                 <div class="table-responsive">
                     <div id="basic-datatables_wrapper" class="dataTables_wrapper dt-bootstrap4">
                     <?	
                     $s=mysql_query("SELECT * FROM `i_events` ORDER BY `id` ASC");
                     if (mysql_num_rows($s) > 0)
                     {
                         ?>
                         <table id="basic-datatables" class="display table table-striped table-hover dataTable">
                             <thead>
                                 <tr>
                                     <th>Название</th>
                                     <th>Описание</th>
                                     <th>Дата</th>
                                     <th>Ссылка</th>
                                     <th>Статус</th>
                                     <!-- <th>Тип</th>
                                     <th>ФИ</th>
                                     <th>Телефон</th>
                                     <th>Логин</th>
                                     <th>Пароль</th> -->
                                     <!-- <th>Статус</th> -->
                                 </tr>
                             </thead>
                         <?
                         while($r=mysql_fetch_array($s))
                         {
                             ?>
                             <tr role="row" class="<?=(($i%2)==1 ? 'odd' : 'even')?>" onclick="location.href='add.php?edit=<?=$r["id"]?>>'" style="cursor:pointer">
                                 <td><?=$r["name"]?></td>
                                 <td><?=$r["description"]?></td>
                                 <td><?=$r["date"]?></td>
                                 <td><?=$r["link"]?></td>
                                 <td><?=(intval($r["active"]) == 1 ? 'Актуальный' : 'Неактуальный')?></td>
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
