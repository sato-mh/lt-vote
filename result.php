<?php

include_once("sessions.php");
include_once("functions.php");

$result_raw0 = file_get_contents("http://116.118.226.94:8080/votes?conferenceId=5&term=0");
$result0 = json_decode($result_raw0);
$result0 = get_object_vars($result0);

$result_raw1 = file_get_contents("http://116.118.226.94:8080/votes?conferenceId=5&term=1");
$result1 = json_decode($result_raw1);
$result1 = get_object_vars($result1);

// $result0 = [
//     "0" => 2,
//     "1" => 3,
//     "2" => 1
// ];

// $result1 = $result0;

arsort($result0);
arsort($result1);

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>第5回LT大会 結果</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/musubii@2.2.0/docs/css/musubii.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="section">
        <div class="hero group">
            <h1 class="heading is-xxl is-strong">第5回LT大会 結果ページ</h1>
        </div>
        <div class="inner group">
            <div class="group">
                <h2 class="heading is-xl">結果発表</h2>
            </div>

            <div class="group">
                <h3 class="heading is-lg">前半</h3>
                <table class="table is-stripe is-line is-center">
                    <tr class="box is-paint">
                        <th width="100">順位</th>
                        <th>LT名</th>
                        <th width="100">得票数</th>
                    </tr>
<?php
$count = 1;
foreach ($result0 as $speakerId => $point) {
?>
                    <tr>
                        <td><?= h($count) ?></td>
                        <td><?= h($sessions_term0[$speakerId]) ?></td>
                        <td><?= h($point) ?></td>
                    </tr>
<?php
$count += 1;
}
?>
                </table>
            </div>

            <div class="group">
                <h3 class="heading is-lg">後半</h3>
                <table class="table is-stripe is-line is-center">
                    <tr class="box is-paint">
                        <th width="100">順位</th>
                        <th>LT名</th>
                        <th width="100">得票数</th>
                    </tr>
<?php
$count = 1;
foreach ($result1 as $speakerId => $point) {
?>
                    <tr>
                        <td><?= h($count) ?></td>
                        <td><?= h($sessions_term1[$speakerId]) ?></td>
                        <td><?= h($point) ?></td>
                    </tr>
<?php
$count += 1;
}
?>
                </table>
            </div>

        </div>

        <div class="footer groups is-center">
            <small>このページは <a href="https://qrac.github.io/musubii/" target="_blank">Musubii</a> を使って作られています。</small>
        </div>
    </div>
    <script
			  src="https://code.jquery.com/jquery-3.2.1.min.js"
			  integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
			  crossorigin="anonymous"></script>
</body>
</html>