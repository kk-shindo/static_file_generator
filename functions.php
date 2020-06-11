<?php
class Create {

    function __construct($data) {
        $this->data = $data;
        $this->extension = $this->data["extension"];
        $this->site_url = $this->data["site_url"];
        $this->paths = $this->data["paths"];
        $this->replace = $this->data["replace"];
        $this->output_dir = "output";
    }

    /*
    * outputディレクトリの初期化
    * ToDo: 雑なのでスマートにしたい
    */
    function delete_output_files() {
        $this->delete_zip();
        $this->delete_files();
        $this->delete_dirs();
    }

    // zipの削除
    function delete_zip() {
        if(!empty(glob("output.zip"))) {
            unlink("output.zip");
        }
    }

    // ファイルの削除
    function delete_files() {
        foreach(glob("output/*/*/*/*.*") as $file) {
            // globで取得したファイルをunlinkで1つずつ削除していく
            unlink($file);
        }
        foreach(glob("output/*/*/*.*") as $file) {
            // globで取得したファイルをunlinkで1つずつ削除していく
            unlink($file);
        }
        foreach(glob("output/*/*.*") as $file) {
            // globで取得したファイルをunlinkで1つずつ削除していく
            unlink($file);
        }
    }

    // ディレクトリの削除
    function delete_dirs() {
        foreach(glob("output/*/*/*") as $dir) {
            // globで取得したディレクトリをrmdirで1つずつ削除していく
            rmdir($dir);
        }
        foreach(glob("output/*/*") as $dir) {
            // globで取得したディレクトリをrmdirで1つずつ削除していく
            rmdir($dir);
        }
        foreach(glob("output/*") as $dir) {
            // globで取得したディレクトリをrmdirで1つずつ削除していく
            rmdir($dir);
        }
    }

    function get_target_url($path) {
        // $site_urlを整形
        $site_url = rtrim($this->site_url, '/');
        // $pathを整形
        if(substr($path, 0, 1) !== "/") {
            $path = "/{$path}";
        }
        if(substr($path, -1, 1) !== "/") {
            $path = "{$path}/";
        }
        return $site_url.$path;
    }

    /*
    * ファイル生成の関数
    * $site_url: https://example.com
    * $path: /article/1/
    * $extension: html or php
    */
    function create_file($path) {
        $target_url = $this->get_target_url($path);
        if(file_get_contents(urldecode($target_url)) === false) return false;
        $buff = file_get_contents(urldecode($target_url)); // urlの内容を読み取る

        if(!empty($this->replace)) {
            foreach($this->replace as $val) {
                if(!empty($val)) {
                    $buff = str_replace($val["search"], $val["replace"], $buff);
                }
            }
        }

        $this->check_dir($path);
        $fname = "output{$path}index.{$this->extension}"; // 生成するファイルのディレクトリとファイル名
        $fhandle = fopen($fname, "w"); // ファイルを書き込みモードで開く。
        fwrite($fhandle, $buff); // ファイルをバイナリモードで書き込む。第二引数に書き込みたい文字列
        fclose($fhandle); // ファイルポインタを閉じる

    }

    function check_dir($full_path) {
        $dirs = explode("/", $full_path);
        $path = "output";
        foreach($dirs as $dir) {
            if($dir === "/") continue;
            $path .= "/".$dir;
            if(!file_exists($path)) {
                mkdir($path, 0777); // パーミッションを指定してフォルダーを生成
                chmod($path, 0777); // Chmod関数でパーミッション変更
            }
        }
    }

    function generate_target_urls() {
        $url_arr = explode("\n", $this->paths); // 行に分割
        $url_arr = array_map("trim", $url_arr); // 各行にtrim()をかける
        $url_arr = array_filter($url_arr, "strlen"); // 文字数が0の行を取り除く
        $url_arr = array_values($url_arr); // これはキーを連番に振りなおしてるだけ
        return $url_arr;
    }

    /*
    * ディレクトリを圧縮する
    * $dir: 圧縮するディレクトリー
    * $file: Zipファイルの保存先
    */
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

    // 実行
    function exec() {
        // 削除
        $this->delete_output_files();

        // path生成
        $paths = $this->generate_target_urls();

        // ファイル生成
        foreach ($paths as $path) {
            $this->create_file($path);
        }

        // zipを生成
        $this->zipDirectory($dir = dirname(__FILE__) . "/{$this->output_dir}/", $file = "./{$this->output_dir}.zip");
    }
}

