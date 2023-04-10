<?php

include __DIR__ . '/src/RenameReplacer.php';

use epitcher\RenameReplacer;

$file_path = "config.json";

$config = json_decode(file_get_contents($file_path), true);

$rename_replacer = new RenameReplacer($config);

$rename_replacer->run();

?>
