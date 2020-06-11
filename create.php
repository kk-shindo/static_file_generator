<?php
session_start();

$session = $_SESSION;
unset($_SESSION["data"]);

$is_empty_session = empty($session) || !isset($session["data"]);

if($is_empty_session) {
//読み込みエラーもしくは変数がない時の処理
$html = <<<__HTML__
<h1>Failed!</h1>
<p>Could not read file</p>
__HTML__;
} else {
$html = <<<__HTML__
<h1>Success!</h1>
<p><a href="./output.zip" class="btn btn-primary">download</a></p>
__HTML__;
}

if(!$is_empty_session) {
    require_once('./functions.php');
    $create = new Create($session["data"]);
    $create->exec();
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Static File Generator</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
</head>
<body class="container">
<?= $html ?>
<p><a href="../" class="btn btn-secondary">Return</a></p>

<?php if(!$is_empty_session): ?>
<ul>
    <?php foreach($create->generate_target_urls() as $url): ?>
    <li><a href="<?= $create->get_target_url($url) ?>" targe="_blank"><?= $create->get_target_url($url) ?></a></li>
    <?php endforeach; ?>
</ul>
<?php endif; ?>

</body>
</html>
