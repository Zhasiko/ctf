<?php


$lang = "ru";
$title = "Задача #" . $_GET["id"];
$keywords = "";
$description = "";
require($_SERVER["DOCUMENT_ROOT"] . "/libs/header.php");

if ($api->Managers->check_auth() == true) {
    if (
        (
            $api->Managers->man_block == 3
        ) &&
        (isset($_GET["id"]) && intval($_GET["id"]) != 0)
    ) {

        $sql_wh = "";
        // if ($api->Managers->man_block == 2)
        //     $sql_wh = " AND (`status`=1 OR ( (`status`=2 OR `status`=3 OR `status`=4) AND `id_man`='" . $api->Managers->man_id . "') )";
        // else if ($api->Managers->man_block == 3)
        //     $sql_wh = " AND ( ( (`status`=1 OR `status`=2) AND (`id_exam`='" . $api->Managers->man_id . "' OR `id_exam`=0 OR `id_exam` IS NULL) ) OR ( (`status`=3 OR `status`=4) AND `id_exam`='" . $api->Managers->man_id . "') )";
        // else if ($api->Managers->man_block == 4)
        //     $sql_wh = " AND `id_broker`='" . $api->Managers->man_id . "'";

        $id = intval($_GET["id"]);
		
        $s = mysql_query("SELECT * FROM `i_order` WHERE `id`='" . $id . "'" . $sql_wh . " LIMIT 1");
		// echo $s;
        if (mysql_num_rows($s) > 0) {
            $r = mysql_fetch_array($s);

            $id = $r["id"];
            $name = $r["task_name"];
            $link = $r["link"];
            $description = $r["description"];
            $points = $r["points"];
            $flag = $r["flag"];
            $task_type = $r["task_name"];
            $level = $r["level"];
            $solving_avg = $r["solving_avg"];
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
						float: left;
						position: relative;
						left: 3%;
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
                        margin-bottom: 20px; /* Increased margin */
                        margin-right: 20px; /* Added margin-right */
                    }


					.button-start{
						margin-bottom: 30%;
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
                        margin-top: 10px; /* Increased margin */
                    }

                    .submit-button:hover {
                        background-color: #218838;
                    }

                    .stars {
                        color: #ffd700; /* Set color to gold */
                        font-size: 20px;
                        margin-left: 10px; /* Add margin */
                    }

					.star-avg {
						float: right;
					}

					.solving-avg {
						margin-left: 20px;
					}

					.task-info-type-level {
                        font-size: 18px;
                        margin-bottom: 30px;
                        color: white;
						width: 60%;
						
                    }

                    .points {
                        background-color: #dc3545; /* Change background color */
                        color: white;
                        padding: 5px 10px;
                        border-radius: 5px;
                        display: inline-block;
                        font-weight: bold;
                        margin-bottom: 10px; /* Add margin */
                    }
                </style>
            </head>

            <body>

                <div class="container">
                    <div class="task-name"><?php echo $name; ?></div>

                    <div class="task-info-type-level">
                        <?php echo $task_type;
                        // Convert level to stars
                        $stars = '';
                        if ($level == 'hard') {
                            $stars = '★★★★★';
                        } else if ($level == 'medium') {
                            $stars = '★★★';
                        } else if ($level == 'easy') {
                            $stars = '★';
                        }
						?>
						<span class = "star-avg">
							<?php 
							echo "<span class='stars'>$stars</span>";
							echo "<span class='solving-avg'> $solving_avg</span>";
							?>
                        </span>
                    </div>

                    <div class="task-info">
                        <span class="points"><?php echo $points; ?></span>
                    </div>

                    <div class="description">
                        <?php echo $description; ?>
                    </div>

                    <a href="<?php echo $link; ?>" class="button button-start" target="_blank">Start the Challenge</a> <!-- Open link in a new tab -->

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
                                    $('#flag-result').html('<p style="color: green; font-size: 20px;">You solved this task!</p>');
                                } else {
                                    $('#flag-result').html('<p style="color: red; font-size: 20px;">Incorrect flag. Please try again.</p>');
                                }
                            });
                        });
                    </script>
                </div>

            </body>
			
<?php
        }

    } else if
	(	
		(
			$api->Managers->man_block == 1 || // админ 
            $api->Managers->man_block == 2
		) &&
		(isset($_GET["id"]) && intval($_GET["id"]) != 0)
	)
	{

        $sql_wh = "";
        if ($api->Managers->man_block == 2)
            $sql_wh = " AND (`status`=1 OR ( (`status`=2 OR `status`=3 OR `status`=4) AND `id_man`='" . $api->Managers->man_id . "') )";
        else if ($api->Managers->man_block == 3)
            $sql_wh = " AND ( ( (`status`=1 OR `status`=2) AND (`id_exam`='" . $api->Managers->man_id . "' OR `id_exam`=0 OR `id_exam` IS NULL) ) OR ( (`status`=3 OR `status`=4) AND `id_exam`='" . $api->Managers->man_id . "') )";
        else if ($api->Managers->man_block == 4)
            $sql_wh = " AND `id_broker`='" . $api->Managers->man_id . "'";

        $id = intval($_GET["id"]);
        $s = mysql_query("SELECT * FROM `i_order` WHERE `id`='" . $id . "'" . $sql_wh . " LIMIT 1");
        if (mysql_num_rows($s) > 0) {
            $r = mysql_fetch_array($s);

            $id = $r["id"];
            $name = $r["task_name"];
            $link = $r["link"];
            $description = $r["description"];
            $points = $r["points"];
            $flag = $r["flag"];
            $task_type = $r["task_name"];
            $level = $r["level"];
            $solving_avg = $r["solving_avg"];
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
						float: left;
						position: relative;
						left: 3%;
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
                        margin-bottom: 20px; /* Increased margin */
                        margin-right: 20px; /* Added margin-right */
                    }


					.button-start{
						margin-bottom: 30%;
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
						color: white; /* Add this line to set text color to white */
					}

                    .submit-button {
                        padding: 10px 20px;
                        background-color: #28a745;
                        color: #fff;
                        border: none;
                        border-radius: 5px;
                        cursor: pointer;
                        transition: background-color 0.3s;
                        margin-top: 10px; /* Increased margin */
                    }

                    .submit-button:hover {
                        background-color: #218838;
                    }

                    .stars {
                        color: #ffd700; /* Set color to gold */
                        font-size: 20px;
                        margin-left: 10px; /* Add margin */
                    }

					.star-avg {
						float: right;
					}

					.solving-avg {
						margin-left: 20px;
					}

					.task-info-type-level {
                        font-size: 18px;
                        margin-bottom: 30px;
                        color: white;
						width: 60%;
						
                    }

                    .points {
                        background-color: #dc3545; /* Change background color */
                        color: white;
                        padding: 5px 10px;
                        border-radius: 5px;
                        display: inline-block;
                        font-weight: bold;
                        margin-bottom: 10px; /* Add margin */
                    }
                </style>
            </head>

            <body>

                <div class="container">
                    <div class="task-name"><?php echo $name; ?></div>

                    <div class="task-info-type-level">
                        <?php echo $task_type;
                        // Convert level to stars
                        $stars = '';
                        if ($level == 'hard') {
                            $stars = '★★★★★';
                        } else if ($level == 'medium') {
                            $stars = '★★★';
                        } else if ($level == 'easy') {
                            $stars = '★';
                        }
						?>
						<span class = "star-avg">
							<?php 
							echo "<span class='stars'>$stars</span>";
							echo "<span class='solving-avg'> $solving_avg</span>";
							?>
                        </span>
                    </div>

                    <div class="task-info">
                        <span class="points"><?php echo $points; ?></span>
                    </div>

                    <div class="description">
                        <?php echo $description; ?>
                    </div>

                    <a href="<?php echo $link; ?>" class="button button-start" target="_blank">Start the Challenge</a> <!-- Open link in a new tab -->

					<form id="flag-form" action="" method="post">
						<div id="user-flag" class="input-field"><?php echo $flag; ?></div>
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
                                    $('#flag-result').html('<p style="color: green; font-size: 20px;">You solved this task!</p>');
                                } else {
                                    $('#flag-result').html('<p style="color: red; font-size: 20px;">Incorrect flag. Please try again.</p>');
                                }
                            });
                        });
                    </script>
                </div>

            </body>
			
	<?php
        }

	
	}
} else
    require($_SERVER["DOCUMENT_ROOT"] . "/text_noAuth.php");
require($_SERVER["DOCUMENT_ROOT"] . "/libs/footer.php");

?>
