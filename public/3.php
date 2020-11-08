<?php

$limit = 20;
$pageNum = isset($_GET['page']) ? $_GET['page'] - 1 : 0;
$offset = $pageNum * $limit;

require '../DB.php';
$config = require  '../config.php';
$db = DB::getInstance($config['db']);

$total = ceil($db->total('csv') / $limit);
$pages = [];

for($i = 1; $i <= $total; $i++) {
    $pages[$i] = "?page=$i";
}

$data = $db->get('csv', ["LIMIT $limit", "OFFSET $offset"]);

$url = $_SERVER['SCRIPT_NAME'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Test 2020-11-08:2</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <style>
        .table td:not([class]) {
            overflow-wrap: break-word;
            word-wrap: break-word;
            -ms-word-break: break-all;
            word-break: break-word;
            -ms-hyphens: auto;
            -moz-hyphens: auto;
            -webkit-hyphens: auto;
            hyphens: auto;
        }
        .table tr:nth-child(even) td {
            background: #f1f1f1;
        }
    </style>
</head>
<body>

    <div class="container">
        <table class="table">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Value1</th>
                <th>Value2</th>
                <th>Value3</th>
            </tr>
            <?php foreach($data as $item) : ?>
            <tr>
                <td class=""><b><?=$item['id']?></b></td>
                <td><?=$item['name']?></td>
                <td><?=$item['value1']?></td>
                <td><?=$item['value2']?></td>
                <td><?=$item['value3']?></td>
            </tr>
            <?php endforeach; ?>
        </table>
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <?php foreach($pages as $i => $page) : ?>
                    <li class="page-item<?=$i-1 == $pageNum ? ' active' : ''?>"><a class="page-link" href="<?=$url?><?=$i == 1 ? '' : $page?>"><?=$i?></a></li>
                <?php endforeach;?>
            </ul>
        </nav>
    </div>

</body>
</html>
