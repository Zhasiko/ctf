<?php


$lang = "ru";
$title = "Статистика";
$keywords = "";
$description = "";
require($_SERVER["DOCUMENT_ROOT"] . "/libs/header.php");

if ($api->Managers->check_auth() == true) {

    require_once '../get_encr_key.php';

    
    $user_id = $api->Managers->man_id;
    $stegano_solved = 0;
    $web_solved = 0;
    $crypto_solved = 0;
    $etc_solved = 0;

    if($api->Managers->man_block == 3){
        $sql_query = "SELECT * FROM `i_solved` where `user_id` =".$api->Managers->man_id;
    }
    else{
        $sql_query = "SELECT * FROM `i_solved`";
    }
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
    if(!isset($procent_crypto)){
        $procent_crypto = 0;
    }
    if(!isset($procent_web)){
        $procent_web = 0;
    }
    if(!isset($procent_stegano)){
        $procent_stegano = 0;
    }
    if(!isset($procent_etc)){
        $procent_etc = 0;
    }
    // Сбор процентов в массив
    $percentages = array(
        'Стеганография' => $procent_stegano,
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
            $all_points = 0;
            while($r=mysql_fetch_array($s))
            {
                $all_points = $r['points'] + $all_points;
            }

            $all_tasks_cnt = mysql_num_rows($s);
            $user_procent = $total_solved / $all_tasks_cnt * 100;
            if($api->Managers->man_block == 3){
        ?>
        <div class="card" style="display: flex; flex-direction: column; justify-content: center; align-items: center;"> <!-- Изменяем размер карты на 200x200 пикселей и применяем flex-контейнер -->
            <h2>% Решенных задач пользователя</h2>
            <svg class="chart progress-ring" width="200" height="200"> <!-- Изменяем размеры svg -->
                <circle class="progress-ring-circle" stroke="#32E6B7" stroke-width="20" fill="transparent" r="70" cx="150" cy="150"/> <!-- Изменяем радиус круга и его координаты -->
            </svg>
            <p >% ваших решенных задач: <?php echo $user_procent?>%</p> <!-- Добавляем отступ сверху в 10px -->
        </div>
        <?php
            }
        ?>
        <div>

        <?php
            $sql_query = "SELECT points FROM `i_manager_users` where `id` =".$api->Managers->man_id;
            $s=mysql_query($sql_query);
            while($r=mysql_fetch_array($s))
            {
                $user_points = $r['points'];
            }

            $sql_query = "SELECT DISTINCT `task_id` FROM `i_solved`";
            $s=mysql_query($sql_query);
            $total_solved_unique = mysql_num_rows($s);
            $avg_solved_unique = $total_solved_unique / $all_tasks_cnt * 100;
            if($api->Managers->man_block == 3){
        ?>
        <div class="card" style="height: 220px;">
            <h2>КОЛ-ВО МОИХ БАЛЛОВ ОТ МАКСИМАЛЬНОГО</h2>
            <p><?php echo "Количество ваших баллов: ". $user_points?></p>
            <p><?php echo "Макс количество баллов: ". $all_points?></p>
        </div>
        <?php
            }else{
        ?>
        <div class="card" style="height: 220px;">
            <h2>КОЛ-ВО РЕШЕННЫХ УНИКАЛЬНЫХ ЗАДАЧ</h2>
            <p><?php echo "Количество решенных задач: ". $total_solved_unique?></p>
            <p><?php echo "Количество всех задач: ". $all_tasks_cnt?></p>
            <p><?php echo $avg_solved_unique. "% от общего (". $all_tasks_cnt.')'?></p>
        </div>
        <?php
            }
            $sql_query = "SELECT * FROM `i_manager_users` where `id_section` = 3";
            $s=mysql_query($sql_query);
            $students_cnt = mysql_num_rows($s);

            $avg_students_solved = $total_solved / $students_cnt; 
            $avg_students_solved = round($avg_students_solved, 1);
        ?>
       <div class="card" style="height: 222px; padding: 10px; overflow: hidden;">
            <h2 style="font-size: 16px;">CРЕД КОЛ-ВО РЕШЕННЫХ ЗАДАЧ ПОЛЬЗОВАТЕЛЯМИ</h2>
            <p style="margin: 5px 0;"><?php echo "Количество задач: ".$total_solved ?></p>
            <p style="margin: 5px 0;"><?php echo "Количество студентов: ".$students_cnt ?></p>
            <p style="margin: 5px 0;"><?php echo round($avg_students_solved, 1). " задач в среднем" ?></p>
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
