<?
$lang="ru";
$title="Задача #".$_GET["id"];
$keywords="";
$description="";
require($_SERVER["DOCUMENT_ROOT"]."/libs/header.php");
if ($api->Managers->check_auth() == true)
{
	if (
		(
			$api->Managers->man_block == 1 || // админ 
			$api->Managers->man_block == 2 
		) &&
		(isset($_GET["id"]) && intval($_GET["id"]) != 0)
	)
	{
		
		$sql_wh = "";
		if ($api->Managers->man_block == 2)
			$sql_wh = " AND (`status`=1 OR ( (`status`=2 OR `status`=3 OR `status`=4) AND `id_man`='".$api->Managers->man_id."') )";
		else if ($api->Managers->man_block == 3)
			$sql_wh = " AND ( ( (`status`=1 OR `status`=2) AND (`id_exam`='".$api->Managers->man_id."' OR `id_exam`=0 OR `id_exam` IS NULL) ) OR ( (`status`=3 OR `status`=4) AND `id_exam`='".$api->Managers->man_id."') )";
		else if ($api->Managers->man_block == 4)
			$sql_wh = " AND `id_broker`='".$api->Managers->man_id."'";

		$id = intval($_GET["id"]);
		$s = mysql_query("SELECT * FROM `i_order` WHERE `id`='".$id."'".$sql_wh." LIMIT 1");
		if (mysql_num_rows($s) > 0)
		{
			$r=mysql_fetch_array($s);
			
			
			$id = $r["id"];
			$name = $r["task_name"];
			$link = $r["link"];
			$description = $r["description"];
			$points = $r["points"];
			$flag = $r["flag"];
			$task_type = $r["task_name"];
			$level = $r["level"];
			$solving_avg = $r["solving_avg"];
			
			$task = array();

			// array_push($task, $id, $name, $link, $description, $points, $flag, $task_type, $level, $solving_avg);
			
			// print_r($task);
			?>
			<head>
				<!-- Other meta tags and styles -->
				<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
				<!-- Other scripts -->
			
			<style>
			body {
				font-family: Arial, sans-serif;
				margin: 0;
				padding: 0;
				background-color: #1f222e;
			}
			
			.container {
				max-width: 800px;
				margin: 50px auto;
				padding: 20px;
				background-color: #1f222e;
				border-radius: 8px;
				box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
			}
			
			.task-name {
				font-size: 32px;
				font-weight: bold;
				margin-bottom: 10px;
				color: white;
			}
			
			.task-info {
				font-size: 18px;
				margin-bottom: 10px;
				color: white;
			}
			
			.description {
				margin-bottom: 20px;
				color: white;
			}
			
			.button {
				display: inline-block;
				padding: 10px 20px;
				background-color: #007bff;
				color: #fff;
				text-decoration: none;
				border-radius: 5px;
				transition: background-color 0.3s;
			}
			
			.button:hover {
				background-color: #0056b3;
			}
			
			.input-field {
				padding: 10px;
				width: calc(100% - 80px);
				margin-bottom: 10px;
				border-radius: 5px;
				border: 1px solid #ccc;
			}
			
			.submit-button {
				padding: 10px 20px;
				background-color: #28a745;
				color: #fff;
				border: none;
				border-radius: 5px;
				cursor: pointer;
				transition: background-color 0.3s;
			}
			
			.submit-button:hover {
				background-color: #218838;
			}
			</style>
			</head>
			<body>
			
			<div class="container">
				<div class="task-name"><?php echo $name; ?></div>
				<div class="task-info">
					<?php echo $task_type; ?>, <?php echo $level; ?>, <?php echo $solving_avg; ?>
				</div>
				<div class="task-info">Points: <?php echo $points; ?></div>
			
				<div class="description">
					<?php echo $description; ?>
				</div>
			
				<a href="<?php echo $link; ?>" class="button">Start the Challenge</a>
			
				<form id="flag-form" action="" method="post">
					<input type="text" id="user-flag" name="user_flag" placeholder="Enter Flag" class="input-field">
					<button type="submit" id="submit-flag" class="submit-button">Submit</button>
				</form>
				<div id="flag-result"></div>
			
				<script>
					$(document).ready(function() {
						$('#flag-form').on('submit', function(e) {
							e.preventDefault(); // Prevent form submission

							// Get user input
							var userFlag = $('#user-flag').val().trim();
							var flag = '<?php echo $flag; ?>'; // Fetch flag from PHP

							// Compare flags
							if (userFlag === flag.trim()) {
								$('#flag-result').html('<p style="color: green;">You solved this task!</p>');
							} else {
								$('#flag-result').html('<p style="color: red;">Incorrect flag. Please try again.</p>');
							}
						});
					});
				</script>
			</div>
			
			</body>
			<?
		}	
	}
}	

else
	require($_SERVER["DOCUMENT_ROOT"]."/text_noAuth.php");
require($_SERVER["DOCUMENT_ROOT"]."/libs/footer.php");?>
`