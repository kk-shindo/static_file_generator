<?php
session_start();

//読み込みエラーもしくは変数がない時の処理
if(empty($_SESSION) || !isset($_SESSION["data"])) {
$html = <<<__HTML__
<h1>Failed!</h1>
<p>Could not read file</p>
<p><a href=\"../\">Return</a></p>
__HTML__;
} else {
    // POSTした内容を変数に入れる
    $post = $_SESSION["data"];
    unset($_SESSION["data"]);

    require_once("./functions.php");
    $create = new Create($post);
    $create->exec();

$html = <<<__HTML__
<h1>Success!</h1>
<p><a href="./output.zip">download</a></p>
<p><a href="../">Return</a></p>
__HTML__;
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
</body>
</html>
