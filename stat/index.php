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
    $total_solved_user = 0;
    


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
           if($r['user_id'] == $user_id){
            $total_solved_user = $total_solved_user + 1;
            }
        }
        $solved = array(
            'Стеганография' => $stegano_solved,
            'Веб' => $web_solved,
            'Криптография' => $crypto_solved,
            'Прочее' => $etc_solved
        );
        arsort($solved);
    }
    $sql_query= "SELECT `task_type`, COUNT(*) AS `count` FROM `i_order` GROUP BY `task_type`";
    $s=mysql_query($sql_query);
    while($r=mysql_fetch_array($s))
    {
        if($r['task_type'] == 'crypto'){
            $total_crypto = $r['count'];
            $procent_crypto = $crypto_solved / $total_crypto * 100;
        }
        if($r['task_type'] == 'web'){
            $total_web = $r['count'];
            $procent_web = $web_solved / $total_web * 100;
        }
        if($r['task_type'] == 'stegano'){
            $total_stegano = $r['count'];
            $procent_stegano = $stegano_solved / $total_stegano * 100;
        }
        if($r['task_type'] == 'прочее'){
            $total_etc = $r['count'];
            $procent_etc = $etc_solved / $total_etc * 100;
        }
    }
    // Сбор процентов в массив
    $percentages = array(
        'Стеганография' => isset($procent_stegano),
        'Веб' => $procent_web,
        'Криптография' => $procent_crypto,
        'Прочее' => $procent_etc
    );

    // Сортировка массива по значениям в порядке убывания
    arsort($percentages);
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
            background-color: #2E2E3E;
            border-radius: 10px;
            padding: 20px;
            flex: 1 1 300px;
            max-width: 300px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            box-sizing: border-box;
        }

        .card:hover {
				transform: scale(1.02);
				box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.4);
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
        .progress-ring {
            height: 300px;
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
            <div class="bar-container" style="margin-top: 50px;"> <!-- Добавляем отступ сверху в 20px -->
                <?php
                // Вывод отсортированных значений с процентами и названиями
                foreach ($percentages as $category => $percentage) {
                    $height = $percentage + 1;
                    echo '<div class="bar-wrapper">';
                    echo '<div class="bar" style="height: ' . $height . 'px;"></div>';
                    echo '<div class="bar-label">' . round($percentage, 2) . '%</div>'; 
                    echo '</div>';
                }
                ?>
            </div>
        </div>

            <p>Топ решенные категории</p>
            <ul>
                <?php
                // Вывод отсортированных значений       
                foreach ($solved as $category => $count) {
                    echo "<li>$category - $count</li>";
                }
                ?>
            </ul>
        </div>
            
        <?php
            $sql_query= "SELECT * FROM `i_order`";
            $s=mysql_query($sql_query);
            $all_tasks_cnt = mysql_num_rows($s);

            $user_procent = $total_solved_user / $all_tasks_cnt * 100;
        ?>
        <div class="card" style="display: flex; flex-direction: column; justify-content: center; align-items: center;"> <!-- Изменяем размер карты на 200x200 пикселей и применяем flex-контейнер -->
            <h2>% Решенных задач пользователя</h2>
            <svg class="chart progress-ring" width="200" height="200"> <!-- Изменяем размеры svg -->
                <circle class="progress-ring-circle" stroke="#32E6B7" stroke-width="20" fill="transparent" r="70" cx="150" cy="150"/> <!-- Изменяем радиус круга и его координаты -->
            </svg>
            <p >% ваших решенных задач: <?php echo $user_procent?>%</p> <!-- Добавляем отступ сверху в 10px -->
        </div>
        <?php
            $avg_solved = $total_solved / $all_tasks_cnt * 100;
        ?>
        <div>
        <div class="card" style="height: 220px;">
            <h2>КОЛ-ВО МОИХ РЕШЕННЫХ ЗАДАЧ</h2>
            <p><?php echo "Количество: ". $total_solved?></p>
            <p><?php echo $avg_solved. "% от общего (". $all_tasks_cnt.')'?></p>
        </div>
        <div class="card">
            <h2>CРЕД КОЛ-ВО РЕШЕННЫХ ЗАДАЧ ПОЛЬЗОВАТЕЛЯМИ</h2>
            <p><?php echo "Количество: 2"?></p> 
            <!-- . $total_solved -->
            <p><?php echo  "40% от общего (5)"?></p>
            <!-- $avg_solved. -->
            <!-- . $all_tasks_cnt.' -->
        </div>
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

            setProgress(<?php echo $user_procent; ?>);
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
