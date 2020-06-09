<?php
    $post = $_POST;
    echo "<pre>"; var_dump($post); echo "</pre>";
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
<body class="container mt-5">
    <h1 class="text-center">Static File Generator</h1>
    <form action="./" method="post" id="app" class="mt-5">
        <table class="table">
            <tbody>
                <tr>
                    <th>
                        <label for="fSiteUrl">Extension</label>
                    </th>
                    <td colspan="2">
                        <?php
                            if(!empty($err) && in_array("extension", $err)) {
                                echo "<p class=\"badge badge-danger text-wrap\">empty</p>";
                            }
                        ?>
                        <label class="mr-5"><input type="radio" name="extension" value="html" class="mr-2" checked>html</label>
                        <label class="mr-5"><input type="radio" name="extension" value="php" class="mr-2">php</label>
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="fSiteUrl">Site URL</label>
                    </th>
                    <td colspan="2">
                        <?php
                            if(!empty($err) && in_array("site_url", $err)) {
                                echo "<p class=\"badge badge-danger text-wrap\">empty</p>";
                            }
                        ?>
                        <input type="text" name="site_url" id="fSiteUrl" value="<?php if(isset($post["site_url"]) && $post["site_url"] !== "") echo $post["site_url"] ?>" placeholder="https://example.com" class="w-100">
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="fPaths">PATHs</label><br>
                        <span class="font-weight-light">
                            [example]<br>
                            /article/1/<br>
                            /article/2/<br>
                            /article/3/<br>
                            ...
                        </span>
                    </th>
                    <td colspan="2">
                        <?php
                            if(!empty($err) && in_array("paths", $err)) {
                                echo "<p class=\"badge badge-danger text-wrap\">empty</p>";
                            }
                        ?>
                        <textarea name="paths" id="fUrls" rows="10" class="w-100"><?php if(isset($post["paths"]) && $post["paths"] !== "") echo $post["paths"] ?></textarea>
                    </td>
                </tr>
                <?php /*
                <tr v-for="count in counts">
                    <th v-if="count == 1" v-bind:rowspan="counts">
                        <label for="fReplace">Replace</label>
                    </th>
                    <td>
                        search
                        <textarea v-bind:name="'replace['+(count-1)+'][search]'" id="fReplaceSearch" rows="5" class="w-100"></textarea>
                    </td>
                    <td>
                        replace
                        <textarea v-bind:name="'replace['+(count-1)+'][replace]'" id="fReplaceReplace" rows="5" class="w-100"></textarea>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="2" class="text-right"><button type="button" class="btn btn-info" v-on:click="addReplaceRow">More</button></td>
                </tr>
                */ ?>
                <tr>
                    <td colspan="3" class="text-center"><button type="submit" class="btn btn-primary btn-lg">Create Static Files!</button></td>
                </tr>
            </tbody>
        </table>
    </form>

    <?php /*
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.11"></script>
    <script>
        const app = new Vue({
            el: '#app',
            data: {
                counts: 1
            },
            methods: {
                addReplaceRow: function() {
                    this.counts++
                }
            }
        })
    </script>
    */ ?>
</body>
</html>
