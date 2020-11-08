<?php

require_once 'StringBuilder.php';

function getRandomValue() {
    $charset = ' abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()';
    $length = rand(8, 255);
    $string = '';
    for($i=0; $i<=$length; $i++) {
        $string .= $charset[rand(0, strlen($charset) - 1)];
    }
    return $string;
}

$fileName = isset($argv[1]) ?
    (
        preg_match('/\.csv$/', $argv[1]) ?
            $argv[1]:
            $argv[1] . '.csv'
    )
    :
    'data.csv';

$data = [];

for($i = 1 ; $i <= 200; $i++) {
    //ID, name, value1, value2, value3
    $data[] = [
        'id' => $i,
        'name' => getRandomValue(),
        'value1' => getRandomValue(),
        'value2' => getRandomValue(),
        'value3' => getRandomValue(),
    ];
}

$cvsBuilder = new StringBuilder();

$fileHandler = fopen($fileName, 'w+');
fwrite($fileHandler, $cvsBuilder->generate($data));
fclose($fileHandler);

require_once __DIR__ . '/../DB.php';
$config = require __DIR__ . '/../config.php';

DB::createDataBase($config['db']);
$db = DB::getInstance($config['db']);

$db->createTable("csv", [
    '`id` INT NOT NULL AUTO_INCREMENT',
    '`name` VARCHAR(255) NOT NULL',
    '`value1` VARCHAR(255)',
    '`value2` VARCHAR(255)',
    '`value3` VARCHAR(255)',
    'PRIMARY KEY (id)',
]);

$data = $cvsBuilder->parse(file_get_contents($fileName));
foreach($data as $item) {
    $template = [
        'id' => $item[0],
        'name' => $item[1],
        'value1' => $item[2],
        'value2' => $item[3],
        'value3' => $item[4],
    ];

    $db->save('csv', $template);
}











