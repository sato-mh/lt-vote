<?php

session_start();
$_SESSION['token'] = session_id();

if (isset($_COOKIE['uuid'])){
    $uuid = $_COOKIE['uuid'];
}else{
    $uuid = uniqid();
    setcookie('uuid', $uuid, time() + 60 * 60 * 24 * 7);
}

include_once("sessions.php");
include_once("functions.php");

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>第5回LT大会</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/musubii@2.2.0/docs/css/musubii.min.css">
    <link rel="stylesheet" href="style.css">
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
<?php if ($votingTerm == 0) { ?>
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
<?php } else {
?>
                <p class="group">現在投票を受け付けておりません</p>
<?php
}
?>
            </div>

            <div class="groups is-center">
                <h3 class="heading is-lg">後半</h3>
<?php if ($votingTerm == 1) { ?>
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
<?php } else {
?>
                <p class="group">現在投票を受け付けておりません</p>
<?php
}
?>
            </div>

            <div class="groups">
                <h2 class="heading is-xl">LT一覧</h2>
                <h3 class="heading is-lg">前半 (17:05-)</h3>
                <table class="table is-stripe is-line">
                    <tr class="box is-paint">
                        <th width="100">発表順</th>
                        <th>LT (発表者)</th>
                    </tr>
<?php
foreach ($sessions_term0 as $index => $name) {
?>
                    <tr>
                        <td class="is-center"><?= h($index + 1) ?></td>
                        <td><?= h($name) ?></td>
                    </tr>
<?php
}
?>
                </table>

                <h3 class="heading is-lg">後半 (18:15-)</h3>
                <table class="table is-stripe is-line">
                    <tr class="box is-paint">
                        <th width="100">発表順</th>
                        <th>LT (発表者)</th>
                    </tr>
<?php
foreach ($sessions_term1 as $index => $name) {
?>
                    <tr>
                        <td class="is-center"><?= h($index + 1) ?></td>
                        <td><?= h($name) ?></td>
                    </tr>
<?php
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
                    form.parent(".form-wrapper").html("<p>投稿の最大数を超えたか、投稿に失敗しました。</p>");
                });

                return false;
            });
        });
    </script>
</body>
</html>