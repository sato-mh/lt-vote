<?php

function h($string) {
    return htmlspecialchars($string);
}

session_start();
$_SESSION['token'] = session_id();

if (isset($_COOKIE['uuid'])){
    $uuid = $_COOKIE['uuid'];
}else{
    $uuid = uniqid();
    setcookie('uuid', $uuid, time() + 60 * 60 * 24 * 7);
}

include_once("sessions.php");

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>第5回LT大会</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/musubii@2.2.0/docs/css/musubii.min.css">
    <style type="text/css">
        .hero {
            padding: 50px;
            background: #1c73c7;
            color: #ffffff;
        }
        
        .form-wrapper {
            width: 600px;
            margin: 0 auto;
        }

        .session-button {
            padding-left: 2em;
            padding-right: 2em;
        }
    </style>
</head>
<body>
    <div class="section">
        <div class="hero group">
            <h1 class="heading is-xxl is-strong">第5回LT大会 投票ページ</h1>
        </div>
        <div class="inner group">
            <div class="group">
                <h2 class="heading is-xl">ベストLT賞投票</h2>
            </div>

            <div class="group is-center">
                <h3 class="heading is-lg">前半</h3>
                <div id="term1" class="form-wrapper group">
                    <form class="session form">
                        <div class="group">
                            <div class="field is-middle">
                            <select class="session-id select is-mobile-0">
<?php
foreach ($sessions_term0 as $index => $name) {
?>
                                <option value="<?= h($index) ?>"><?= h($name) ?></option>
<?php
}
?>
                            </select>
                            </div>
                        </div>
                        <div class="group">
                            <input type="hidden" class="term" value="0">
                            <input type="hidden" class="token" value="<?= h($_SESSION['token']) ?>">
                            <div class="btns is-center">
                                <input type="submit" value="送信" class="btn is-plain is-primary is-round session-button">
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="groups is-center">
                <h3 class="heading is-lg">後半</h3>
                <div id="term2" class="form-wrapper group">
                    <form class="session form">
                        <div class="group">
                            <div class="field is-middle">
                            <select class="session-id select is-mobile-0">
<?php
foreach ($sessions_term1 as $index => $name) {
?>
                                <option value="<?= h($index) ?>"><?= h($name) ?></option>
<?php
}
?>
                            </select>
                            </div>
                        </div>
                        <div class="group">
                            <input type="hidden" class="term" value="1">
                            <input type="hidden" class="token" value="<?= h($_SESSION['token']) ?>">
                            <div class="btns is-center">
                                <input type="submit" value="送信" class="btn is-plain is-primary is-round session-button">
                            </div>
                        </div>
                    </form>
                </div>
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
                var url = "dummy.php";
                var request = {
                    userId: "<?php echo h($uuid); ?>",
                    conferenceId: 5,
                    speakerId: Number($(this).children(".session-id").val()),
                    term: Number($(this).children(".term").val()),
                    token: $(this).children(".token").val()
                };

                $.ajax({
                    type: "post",
                    url: url,
                    data: request
                }).then(function(data) {
                    var message = JSON.parse(data)["message"];
                    form.parent(".form-wrapper").html("<p>" + message + "</p>");
                }).catch(function(data) {
                    form.parent(".form-wrapper").html("<p>エラー</p>");
                });

                return false;
            });
        });
    </script>
</body>
</html>