<?php
    $post = $_POST;
    if(!empty($post)) {
        $err = [];
        foreach($post as $key => $val) {
            if(!$val) {
                $err[] = $key;
            }
        }

        if(empty($err)) {
            session_start();
            $_SESSION['data'] = $post;
            header('Location: /create.php');
            exit();
        }
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
    <h1 class="text-center">Static File Generator</h1>
    <form action="./" method="post">
        <table class="table">
            <tbody>
                <tr>
                    <th>
                        <label for="fSiteUrl">Site URL</label>
                    </th>
                    <td>
                        <input type="text" name="site_url" id="fSiteUrl" value="<?php if(isset($post["site_url"]) && $post["site_url"] !== "") echo $post["site_url"] ?>" placeholder="https://example.com" class="w-100">
                        <?php
                            if(!empty($err) && in_array("site_url", $err)) {
                                echo "<p class=\"error\">empty</p>";
                            }
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="fUrls">URLs</label><br>
                        <span class="font-weight-light">
                            [example]<br>
                            https://example.com/article/1/<br>
                            https://example.com/article/2/<br>
                            https://example.com/article/3/<br>
                            ...
                        </span>
                    </th>
                    <td>
                        <textarea name="target_urls" id="fUrls" cols="30" rows="10" class="w-100"><?php if(isset($post["target_urls"]) && $post["target_urls"] !== "") echo $post["target_urls"] ?></textarea>
                        <?php
                            if(!empty($err) && in_array("site_url", $err)) {
                                echo "<p class=\"error\">empty</p>";
                            }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="text-center"><button type="submit">create files</button></td>
                </tr>
            </tbody>
        </table>
    </form>
</body>
</html>