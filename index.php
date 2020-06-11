<?php
$post = $_POST;
$err = [];
if(!empty($post)) {
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

function the_empty_error($key, $error) {
    if(!empty($error) && in_array($key, $error)) {
        echo "<p class=\"badge badge-danger text-wrap\">empty</p>";
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
                        <?php the_empty_error("extension", $err); ?>
                        <label class="mr-5"><input type="radio" name="extension" value="html" class="mr-2" checked>html</label>
                        <label class="mr-5"><input type="radio" name="extension" value="php" class="mr-2">php</label>
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="fSiteUrl">Site URL</label>
                    </th>
                    <td colspan="2">
                        <?php the_empty_error("site_url", $err); ?>
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
                        <?php the_empty_error("paths", $err); ?>
                        <textarea name="paths" id="fUrls" rows="10" class="w-100"><?php if(isset($post["paths"]) && $post["paths"] !== "") echo $post["paths"] ?></textarea>
                    </td>
                </tr>
                <?php
                ?>
                <tr class="replace" v-for="(element, index) in elements">
                    <th v-if="index == 0" v-bind:rowspan="elements.length">
                        <label for="fReplace">Replace</label>
                    </th>
                    <td>
                        search
                        <p><input type="text" v-bind:name="'replace['+(index)+'][search]'" v-model="element.search" class="w-100"></p>
                    </td>
                    <td>
                        replace
                        <p><input type="text" v-bind:name="'replace['+(index)+'][replace]'" v-model="element.replace" class="w-100"></p>
                        <p class="text-right" v-show="elements.length > 1"><button type="button" class="btn btn-danger" v-on:click="removeReplaceRow(index)">Remove</button></p>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="2" class="text-right">
                        <button type="button" class="btn btn-info" v-on:click="addReplaceRow">More</button>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="text-center"><button type="submit" class="btn btn-primary btn-lg">Create Static Files!</button></td>
                </tr>
            </tbody>
        </table>
    </form>

    <?php
        class Posted_Replace_Data {
            function __construct($post) {
                $this->post = $post;
            }
            function is_empty_replace_data() {
                return empty($this->post["replace"]);
            }
            function count_replace_data() {
                return $this->is_empty_replace_data() ? 1 : count($this->post["replace"]);
            }
            function js_the_elements_objects() {
                $arr = [];
                for($i = 0; $i < $this->count_replace_data(); $i++) {
                    if(!$this->is_empty_replace_data()) {
                        $arr[$i]['search'] = $this->post["replace"][$i]["search"];
                        $arr[$i]['replace'] = $this->post["replace"][$i]["replace"];
                    } else {
                        $arr[$i]['search'] = "";
                        $arr[$i]['replace'] = "";
                    }
                }
                return json_encode($arr);
            }
        }
        $posted_replace_data = new Posted_Replace_Data($post);
    ?>
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <script>
        const app = new Vue({
            el: '#app',
            data: {
                elements: <?= $posted_replace_data->js_the_elements_objects(); ?>
            },
            methods: {
                addReplaceRow: function() {
                    this.elements.push({
                        search: '',
                        replace: ''
                    })
                },
                removeReplaceRow: function(index) {
                    const trElm = document.querySelectorAll('.replace');
                    if(trElm.length > 1) {
                        const result = window.confirm('該当の行を削除してもよろしいですか')
                        if(result) {
                            this.elements.splice(index, 1)
                        }
                    }
                }
            }
        })
    </script>
</body>
</html>
