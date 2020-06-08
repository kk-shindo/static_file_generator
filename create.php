<?php
require_once("./functions.php");

// htmlディレクトリの初期化
delete_zip();
delete_html_files();
delete_php_files();
delete_dirs();

session_start();

//読み込みエラーもしくは変数がない時の処理
if(empty($_SESSION) || !isset($_SESSION["data"])) {
    print("<h1>Failed!</h1>");
    print("<p>Could not read file</p>");
    print("<p><a href=\"../\">Return</a></p>");
    return;
}
$post = $_SESSION["data"];
unset($_SESSION["data"]);

$target_urls = explode("\n", $post["target_urls"]); // とりあえず行に分割
$target_urls = array_map("trim", $target_urls); // 各行にtrim()をかける
$target_urls = array_filter($target_urls, "strlen"); // 文字数が0の行を取り除く
$target_urls = array_values($target_urls); // これはキーを連番に振りなおしてるだけ

foreach ($target_urls as $target_url) {
    create_file($target_url, $post["site_url"], $post["extension"]);
}

// 圧縮するディレクトリー
$dir = dirname(__FILE__) . "/html/";

// Zipファイルの保存先
$file = "./html.zip";

zipDirectory($dir, $file);
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
    <h1>Success!</h1>
    <p><a href="./html.zip">download</a></p>
    <p><a href="../">Retrun</a></p>
</body>
</html>
