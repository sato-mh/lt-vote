<?php

include_once("sessions.php");
include_once("functions.php");

$result_raw0 = file_get_contents("http://116.118.226.94:8080/votes?conferenceId=5&term=0");
$result0 = json_decode($result_raw0);
$result0 = get_object_vars($result0);

$result_raw1 = file_get_contents("http://116.118.226.94:8080/votes?conferenceId=5&term=1");
$result1 = json_decode($result_raw1);
$result1 = get_object_vars($result1);

arsort($result0);
arsort($result1);

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>第5回LT大会 結果</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/musubii@2.2.0/docs/css/musubii.min.css">
    <style type="text/css">
        .hero {
            padding: 50px;
            background: #1c73c7;
            color: #ffffff;
        }        
    </style>
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
                    <tr class="is-paint">
                        <th>順位</th>
                        <th>LT名</th>
                        <th>得票数</th>
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
                    <tr class="is-paint">
                        <th>順位</th>
                        <th>LT名</th>
                        <th>得票数</th>
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
    </div>
    <script
			  src="https://code.jquery.com/jquery-3.2.1.min.js"
			  integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
			  crossorigin="anonymous"></script>
    <script>
        $(function () {
            $(".session").submit(function() {
                var form = $(this);
                var url = "http://116.118.226.94:8080/votes";
                var request = {
                    userId: "<?php echo h($uuid); ?>",
                    conferenceId: 5,
                    speakerId: Number(form.find(".session-id").val()),
                    term: Number(form.find(".term").val()),
                    token: form.find(".token").val()
                };

                console.log(JSON.stringify(request));

                $.ajax({
                    type: "post",
                    url: url,
                    contentType: "application/json",
                    data: JSON.stringify(request)
                }).then(function(data) {
                    form.parent(".form-wrapper").html("<p>投稿が完了しました。</p>");
                }).catch(function(data) {
                    var message = JSON.parse(data)["error"];
                    form.parent(".form-wrapper").html("<p>エラー: " + message + "</p>");
                });

                return false;
            });
        });
    </script>
</body>
</html>