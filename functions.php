<?php
function delete_zip() {
    if(!empty(glob("html.zip"))) {
        unlink("html.zip");
    }
}

// ファイルの削除
function delete_html_files() {
    foreach(glob("html/*/*/*/*.html") as $file) {
        // globで取得したファイルをunlinkで1つずつ削除していく
        unlink($file);
    }
    foreach(glob("html/*/*/*.html") as $file) {
        // globで取得したファイルをunlinkで1つずつ削除していく
        unlink($file);
    }
    foreach(glob("html/*/*.html") as $file) {
        // globで取得したファイルをunlinkで1つずつ削除していく
        unlink($file);
    }
}

// ファイルの削除
function delete_php_files() {
    foreach(glob("html/*/*/*/*.php") as $file) {
        // globで取得したファイルをunlinkで1つずつ削除していく
        unlink($file);
    }
    foreach(glob("html/*/*/*.php") as $file) {
        // globで取得したファイルをunlinkで1つずつ削除していく
        unlink($file);
    }
    foreach(glob("html/*/*.php") as $file) {
        // globで取得したファイルをunlinkで1つずつ削除していく
        unlink($file);
    }
}

// ディレクトリの削除
function delete_dirs() {
    foreach(glob("html/*/*/*") as $dir) {
        // globで取得したディレクトリをrmdirで1つずつ削除していく
        rmdir($dir);
    }
    foreach(glob("html/*/*") as $dir) {
        // globで取得したディレクトリをrmdirで1つずつ削除していく
        rmdir($dir);
    }
    foreach(glob("html/*") as $dir) {
        // globで取得したディレクトリをrmdirで1つずつ削除していく
        rmdir($dir);
    }
}

//ファイル生成の関数
function create_file($url, $site_url, $extension="html") {
    $buff = file_get_contents(urldecode($url)); // urlの内容を読み取る
    $key = str_replace($site_url, "", urldecode($url));
    check_dir($key);
    $fname = "html/{$key}/index.{$extension}"; // 生成するファイルのディレクトリとファイル名
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

// ディレクトリを圧縮する
function zipDirectory($dir, $file, $root="") {
    $zip = new ZipArchive();
    $res = $zip->open($file, ZipArchive::CREATE);

    if($res) {
        // $rootが指定されていればその名前のフォルダにファイルをまとめる
        if($root != "") {
            $zip->addEmptyDir($root);
            $root .= DIRECTORY_SEPARATOR;
        }

        $baseLen = mb_strlen($dir);

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(
                $dir,
                FilesystemIterator::SKIP_DOTS
                |FilesystemIterator::KEY_AS_PATHNAME
                |FilesystemIterator::CURRENT_AS_FILEINFO
            ), RecursiveIteratorIterator::SELF_FIRST
        );

        $list = array();
        foreach($iterator as $pathname => $info) {
            $localpath = $root . mb_substr($pathname, $baseLen);

            if($info->isFile()) {
                $zip->addFile($pathname, $localpath);
            } else {
                $res = $zip->addEmptyDir($localpath);
            }
        }

        $zip->close();
    } else {
        return false;
    }
}