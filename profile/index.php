<?php

require_once '../get_encr_key.php';

$lang = "ru";
$title = "Мой профиль";
$keywords = "";
$description = "";
require($_SERVER["DOCUMENT_ROOT"] . "/libs/header.php");

if ($api->Managers->check_auth() == true) {
    if ($api->Managers->man_block == 3) {
        $user_id = $api->Managers->man_id;
        ?>

        <style>

            .profile-control {
                margin-bottom: 10px !important;
                background-color: white !important;
                border: 2px solid #1a237e !important;
                color: #1a237e !important;
                padding: 5px 10px !important;
                border-radius: 5px !important;
                font-weight: bold !important;
            }
			body {
				background-color: #0d1b2a; /* Dark background */
				font-family: 'Arial', sans-serif;
				color: #e0e0e0; /* Light gray text */
			}

			.card {
				display: flex;
				flex-direction: row;
				justify-content: space-between;
				align-items: center;
				border-radius: 20px;
				padding: 20px;
				box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
				width: 80%;
				max-width: 1200px;
				height: 400px;
				background: #0d1b2a;
				background: -webkit-linear-gradient(to right, #0f2027, #203a43, #2c5364);
				background: linear-gradient(to right, #0f2027, #203a43, #2c5364);
				transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
				margin: auto;
				margin-top: 20px; /* Space from top */
			}

			.card:hover {
				transform: scale(1.02);
				box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.4);
			}

			.left-container {
				background: #12192c;
				background: -webkit-linear-gradient(to right, #1b2735, #12192c);
				background: linear-gradient(to right, #1b2735, #12192c);
				flex: 1;
				display: flex;
				flex-direction: column;
				align-items: center;
				justify-content: center;
				height: 100%;
				padding: 20px;
				border-radius: 15px;
				box-shadow: 0 4px 10px 0 rgba(0, 0, 0, 0.3);
				transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
				margin-right: 20px;
				max-width: 35%; /* Adjust width */
			}

			.left-container img {
				border-radius: 50%;
				width: 80%;
				height: auto;
				margin-bottom: 15px; /* Space below image */
			}

			.left-container:hover {
				transform: translateY(-5px);
				box-shadow: 0 8px 20px 0 rgba(0, 0, 0, 0.4);
			}
			.right-container h3 {
				font-size: 1.5em;
				margin-bottom: 20px;
			}

			.right-container table {
				width: 100%;
				font-size: 1em;
				border-collapse: collapse;
			}

			.right-container td {
				padding: 8px;
				border-bottom: 1px solid #e0e0e0;
			}

			.right-container td:first-child {
				font-weight: bold;
			}


			.right-container {
				background: #12192c;
				background: -webkit-linear-gradient(to left, #1b2735, #12192c);
				background: linear-gradient(to left, #1b2735, #12192c);
				flex: 2;
				display: flex;
				flex-direction: column;
				align-items: flex-start;
				justify-content: center;
				height: 100%;
				padding: 20px;
				border-radius: 15px;
				box-shadow: 0 4px 10px 0 rgba(0, 0, 0, 0.3);
				transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
				margin-left: 20px;
			}

			.right-container:hover {
				transform: translateY(-5px);
				box-shadow: 0 8px 20px 0 rgba(0, 0, 0, 0.4);
			}

			.card .title {
				color: #4caf50; /* Green accent for titles */
				font-size: 1.5em;
				margin-bottom: 10px;
			}

			.card .description {
				color: #e0e0e0; /* Light gray for descriptions */
				font-size: 1em;
			}

			.right-container .profile-details {
				line-height: 1.6;
			}

			.right-container .profile-details span {
				color: #4caf50; /* Green accent for details */
			}

			/* Specific styling for icons */
			.left-container .icon, .right-container .icon {
				color: #4caf50; /* Green accent for icons */
			}

			/* Styling for text inside right container */
			.right-container .profile-details .value {
				color: #e0e0e0;
			}


            /* .basic-datatables{
                background-color: #21232c;
            } */
        </style>
		<div class="card">
		<?php
			$cat_value = '';
			$user_name_value = '';
			$login_value = '';
			$phone_value = '';
			$sql_ = '';
			$s = mysql_query("SELECT * FROM `i_manager_users` WHERE `id`='" . $user_id . "'" . $sql_ . " ORDER BY `id` ASC LIMIT 1");
			if (mysql_num_rows($s) > 0) {
				$r = mysql_fetch_array($s);
				$cat_value = $r["id_section"];
				$user_name_value = stripslashes($r["name"]);
				$login_value = $r["login"];
				$phone_value = $r["phone"];
				$points = $r["points"];
				$solved_cnt = $r["task_amount"];
			}

		?>
			<div class="left-container">
				<img class="img-profile" src="https://cdn.pixabay.com/photo/2015/01/08/18/29/entrepreneur-593358__480.jpg" alt="Profile Image">
				<h2 class="gradienttext"><?php echo $user_name_value; ?></h2>
				<p>Студент</p>
			</div>
			<div class="right-container">
				<h3 class="gradienttext">Детали Профиля</h3>
				<table>
					<tr>
						<td>ФИ :</td>
						<td><?php echo $user_name_value; ?></td>
					</tr>
					<tr>
						<td>Номер телефона :</td>
						<td><?php echo $phone_value; ?></td>
					</tr>
					<tr>
						<td>Никнейм :</td>
						<td><?php echo $login_value; ?></td>
					</tr>
					<tr>
						<td>Количество очков :</td>
						<td><?php echo $points; ?></td>
					</tr>
				</table>
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
						<h1 >Решенные задачи: <?php echo $solved_cnt;?></h1>
                    <?
					$order_by = "`id` DESC";

					$i=1;
					$per_page = 100;
					$sql_ = "FROM i_order JOIN i_solved ON i_order.id = i_solved.task_id WHERE i_solved.user_id = ".$user_id;
					$api->Pag->setvars($lang, $_SERVER['PHP_SELF'], $_SERVER['QUERY_STRING'], mysql_result(mysql_query("SELECT COUNT('id') ".$sql_), 0), $per_page, @$_GET['p']);
					if (!empty($_GET['p'])) {$start=$_GET['p'];} else {$start=1; $_GET["p"]=1;}

					$sql_query = "SELECT i_order.*, i_solved.solved_date  ".$sql_." ORDER BY ".$order_by." LIMIT ".$api->Pag->start_from.", ".$per_page."";
					$s=mysql_query($sql_query);
					if (mysql_num_rows($s) > 0)
					{
						$koll = mysql_num_rows(mysql_query("SELECT * ".$sql_));
																													
						?>
						<table id="basic-datatables" class="display table table-striped table-hover dataTable">
							<thead>
								<tr>									
									<th>ID</th>
									<th nowrap>Дата решения</th>
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
							$link = ' style="cursor:pointer;" onclick="location.href=\'../order/more.php?id=' . $r["id"] . '\'" style="cursor:pointer;"';

							$task_name = $r["task_name"];
							$date = $api->Strings->date($lang,$r["solved_date"],'sql','datetime');
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
								<!-- <?
									if (($r["id"] != '') && ($user_id != '') && (mysql_num_rows(mysql_query("SELECT `id` FROM `i_solved` WHERE `task_id`='".$r["id"]."' AND `user_id`='".$user_id."' LIMIT 1")) == 1)){ ?>
										<td<?=$link?> nowrap="nowrap"><?="✅"?></td>
									<? }
								?> -->
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

		</div>

        <?php
    } else {
        require($_SERVER["DOCUMENT_ROOT"] . "/text_noAcces.php");
    }
} else {
    require($_SERVER["DOCUMENT_ROOT"] . "/text_noAuth.php");
}
require($_SERVER["DOCUMENT_ROOT"] . "/libs/footer.php");
?>
