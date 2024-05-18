<?php
require_once '../get_encr_key.php';

$lang = "ru";
$title = "Статистика";
$keywords = "";
$description = "";
require($_SERVER["DOCUMENT_ROOT"] . "/libs/header.php");

if ($api->Managers->check_auth() == true) {
    $user_id = $api->Managers->man_id;
    $stegano_solved = 0;
    $web_solved = 0;
    $crypto_solved = 0;
    $etc_solved = 0;

    $sql_query = "SELECT * FROM `i_solved`";
    $s=mysql_query($sql_query);
    $total_solved = mysql_num_rows($s);

    if ($total_solved > 0)
    {
        while($r=mysql_fetch_array($s))
        {
           if($r['category'] == 'stegano'){
                $stegano_solved = $stegano_solved + 1;
           }
           if($r['category'] == 'web'){
                $web_solved = $web_solved + 1;
           }
           if($r['category'] == 'crypto'){
                $crypto_solved = $crypto_solved + 1;
           }
           if($r['category'] == 'прочее'){
                $etc_solved = $etc_solved + 1;
           }
        }
    }
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <style>
        body {
				background-color: #0d1b2a; /* Dark background */
				font-family: 'Arial', sans-serif;
				color: #e0e0e0; /* Light gray text */
			}

        .container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: flex-start;
            gap: 20px;
            width: 100%;
            max-width: 1200px;
            padding: 20px;
            box-sizing: border-box;
        }

        .card {
            background-color: #2E2E2E;
            border-radius: 10px;
            padding: 20px;
            flex: 1 1 300px;
            max-width: 300px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            box-sizing: border-box;
        }

        .card h2 {
            margin-top: 0;
        }

        .chart {
            height: 200px;
            margin-bottom: 20px;
        }

        .bar-chart, .line-chart, .progress-ring {
            width: 100%;
            height: 200px;
        }

        .progress-ring-circle {
            transition: 0.35s stroke-dashoffset;
            transform: rotate(-90deg);
            transform-origin: 50% 50%;
        }

        .bar {
            background-color: #32E6B7;
            height: 100%;
            width: 40px;
            display: inline-block;
            margin-right: 2px;
        }

        .bar-container {
            height: 100px;
            display: flex;
            align-items: flex-end;
        }

        .line-chart {
            position: relative;
        }

        .line {
            fill: none;
            stroke: #32E6B7;
            stroke-width: 2;
        }

        .point {
            fill: #32E6B7;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <h2>Решенные задачи по категориям</h2>
            <div class="chart bar-chart">
                <div class="bar-container">
                    <div class="bar" style="height: 80%;"></div>
                    <div class="bar" style="height: 85%;"></div>
                    <div class="bar" style="height: 90%;"></div>
                    <div class="bar" style="height: 75%;"></div>
                </div>
            </div>
            <p>Top active pages</p>
            <ul>
                <li>Стеганография - 230,984</li>
                <li>Веб - 83,363</li>
                <li>Криптография - 60,542</li>
                <li>Прочее - 31,873</li>
            </ul>
        </div>

        <div class="card">
            <h2>CLASS BY PROGRESS</h2>
            <svg class="chart progress-ring" width="120" height="120">
                <circle class="progress-ring-circle" stroke="#32E6B7" stroke-width="4" fill="transparent" r="52" cx="60" cy="60"/>
            </svg>
            <p>Task is completed 78%</p>
        </div>

        <div class="card">
            <h2>MINE SOLVED TASKS</h2>
            <p>309,827</p>
            <p>74% of total (420,932)</p>
        </div>

        <div class="card">
            <h2>AVE SOLVED TASKS BY USER</h2>
            <p>150,967.64</p>
            <p>76% of total (198,050.82)</p>
        </div>
    </div>

    <script>
        // JavaScript to handle the progress ring animation
        document.addEventListener('DOMContentLoaded', function () {
            const circle = document.querySelector('.progress-ring-circle');
            const radius = circle.r.baseVal.value;
            const circumference = 2 * Math.PI * radius;

            circle.style.strokeDasharray = `${circumference} ${circumference}`;
            circle.style.strokeDashoffset = `${circumference}`;

            function setProgress(percent) {
                const offset = circumference - percent / 100 * circumference;
                circle.style.strokeDashoffset = offset;
            }

            setProgress(78);
        });
    </script>
</body>
</html>
<?php
} else {
    require($_SERVER["DOCUMENT_ROOT"] . "/text_noAuth.php");
}
require($_SERVER["DOCUMENT_ROOT"] . "/libs/footer.php");
?>
