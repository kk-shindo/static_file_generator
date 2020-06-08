<?php
    session_start();
    $post = $_SESSION['data'];

    //読み込みエラーもしくは変数がない時の処理
    if(empty($post)) {
        print("Could not read file");
        return;
    }

    $target_urls = explode("\n", $post["target_urls"]); // とりあえず行に分割
    $target_urls = array_map('trim', $target_urls); // 各行にtrim()をかける
    $target_urls = array_filter($target_urls, 'strlen'); // 文字数が0の行を取り除く
    $target_urls = array_values($target_urls); // これはキーを連番に振りなおしてるだけ

    foreach ($target_urls as $target_url) {
        create_file($target_url, $post["site_url"]);
    }

    //ファイル生成の関数
    function create_file($url, $site_url) {
        $buff = file_get_contents($url); // urlの内容を読み取る
        $key = str_replace($site_url, "", $url);
        check_dir($key);
        $fname = "html/".$key."index.html"; // 生成するファイルのディレクトリとファイル名
        $fhandle = fopen($fname, "w"); // ファイルを書き込みモードで開く。
        fwrite($fhandle, $buff); // ファイルをバイナリモードで書き込む。第二引数に書き込みたい文字列
        fclose($fhandle); // ファイルポインタを閉じる
    }

    function check_dir($full_path) {
        $dirs = explode("/", $full_path);
        $path = "html";
        foreach($dirs as $dir) {
            if($dir === "/") continue;
            $path .= "/".$dir;
            if(!file_exists($path)) {
                mkdir($path, 0777); // パーミッションを指定してフォルダーを生成
                chmod($path, 0777); // Chmod関数でパーミッション変更
            }
        }
    }

    unset($_SESSION['data']);
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
    <h1>success</h1>
    <a href="/">Retrun</a>
</body>
</html>