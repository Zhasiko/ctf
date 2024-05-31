<?
$lang="ru";
$title="События";
$keywords="";
$description="";
require($_SERVER["DOCUMENT_ROOT"]."/libs/header.php");
?>
<style>
        body {
            /* background-color: #21232c; Цвет фона */
            background-color: #0d1b2a;
        }
        .card {
            /* background-color: #1a2035; Цвет фона */
            background: #12192c;
            /* background: -webkit-linear-gradient(to left, #0f2027, #203a43, #2c5364); */
            
            background: linear-gradient(to left, #1b2735, #12192c);
            border: 1px solid #2e4053;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .card-body{
            color: white !important;
        }

        .cards-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-start; /* Align items to the start to avoid unexpected spacing */
            padding: 20px;
        }

        .left-container {
            background: #0f2027;
            /* background: -webkit-linear-gradient(to right, #1b2735, #12192c); */
            background: linear-gradient(to right, #0f2027, #203a43, #2c5364);
            flex-basis: calc(33.333% - 14px); /* Adjusted for margin-right */
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            /* height: 100%; */
            padding: 20px;
            margin-bottom: 10px;
            border-radius: 15px;
            box-shadow: 0 4px 10px 0 rgba(0, 0, 0, 0.3);
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
            margin-right: 10px; /* Keep this on all but the last item per row */
            min-height: 250px;
        }

        .left-container h2 {
            font-size: 20px; 
            color: #6ba8e5; /* Светло-синий для заголовков */
            font-weight: bold;
            text-align: center;
            margin-bottom: 5px;
            text-decoration: underline; /* Подчеркивание для выделения */
        }

        .left-container .event-date {
            font-size: 16px;
            color: #FF7F50; /* Белый для даты */
            font-weight: bold;
            text-align: center;
            margin-bottom: 2px;
        }

        .left-container .event-time {
            font-size: 16px;
            color: #40E0D0; /* Светло-серый для времени, для различия с датой */
            text-align: center;
            font-weight: normal;
        }

        .left-container h4 {
            font-size: 14px;
            color: #cccccc; /* Светло-серый для описания */
            text-align: center;
            font-weight: normal;
        }

        .left-container:last-child {
            margin-right: 0; /* Removes margin from the last item */
        }

        .left-container:nth-child(3n) {
            margin-right: 0; /* Removes margin from every third item */
        }
        .left-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px 0 rgba(0, 0, 0, 0.4);
        }
        .inactive-container {
            background: #12192c;
            background: -webkit-linear-gradient(to right, #3a3a3a, #505050);
            background: linear-gradient(to right, #3a3a3a, #505050);
            flex-basis: calc(33.333% - 20px); /* Three items per row */
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            /* height: 100%; */
            padding: 20px;
            margin-bottom: 10px;
            border-radius: 15px;
            color: #cccccc !important;
            box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.5) !important;
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
            margin-right: 10px; /* Adjusted margin */
            min-height: 250px;
        }
        .inactive-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px 0 rgba(0, 0, 0, 0.4);
        }
</style>
<?
if ($api->Managers->check_auth() == true && $api->Managers->man_block == 3)
{	
		?>
        
        </style>
		<div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <div id="basic-datatables_wrapper" class="dataTables_wrapper dt-bootstrap4">
                        <div class="cards-container">
                            <?php
                            $s = mysql_query("SELECT *, DATE_FORMAT(date, '%Y-%m-%d %H:%i') as formatted_date FROM `i_events` WHERE `active` = 1 ORDER BY `date` DESC");
                            if (mysql_num_rows($s) > 0) {
                                while ($r = mysql_fetch_array($s)) {
                                    $dateString = $r["formatted_date"];
                                    $parts = explode(" ", $dateString);
                                    $date = $parts[0];
                                    $time = $parts[1];
                                ?>
                                    <div class="left-container" >
                                        <a href="<?php echo $r["link"]; ?>" class="gradienttext" target="_blank">
                                            <h2 class="gradienttext"><?php echo $r["name"]; ?></h2>
                                        </a>
                                        <h4 class="gradienttext"><?php echo $r["description"]; ?></h4>
                                        <div class="event-date"><?php echo $date; ?></div>
                                        <div class="event-time"><?php echo $time; ?></div>
                                        
                                    </div>
                                <?php
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		<?
	}
    else if ($api->Managers->check_auth() == true && ($api->Managers->man_block == 1 || $api->Managers->man_block == 2))
    { ?>

         <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <div id="basic-datatables_wrapper" class="dataTables_wrapper dt-bootstrap4">
                        <div class="cards-container" > 
                            <?php
                             $s = mysql_query("SELECT *, DATE_FORMAT(date, '%Y-%m-%d %H:%i') as formatted_date FROM `i_events` ORDER BY `date` DESC");
                             if (mysql_num_rows($s) > 0) {
                                 while ($r = mysql_fetch_array($s)) {
                                     $dateString = $r["formatted_date"];
                                     $parts = explode(" ", $dateString);
                                     $date = $parts[0];
                                     $time = $parts[1];
                                    $containerClass = $r["active"] == 1 ? "left-container" : "inactive-container";
                            ?>
                                    <div class="<?php echo $containerClass; ?>" onclick="location.href='add.php?edit=<?=$r["id"]?>'">
                                    <a href="<?php echo $r["link"]; ?>" class="gradienttext" target="_blank">
                                        <h2 class="gradienttext"><?php echo $r["name"]; ?></h2>
                                    </a>
                                    <h4 class="gradienttext"><?php echo $r["description"]; ?></h4>
                                    <div class="event-date"><?php echo $date; ?></div>
                                    <div class="event-time"><?php echo $time; ?></div>
                                        
                                    </div>
                            <?php
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <?
    }
    
else
	require($_SERVER["DOCUMENT_ROOT"]."/text_noAuth.php");
require($_SERVER["DOCUMENT_ROOT"]."/libs/footer.php");?>
