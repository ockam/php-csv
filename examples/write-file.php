<?php
require_once('../csv.php');
$data = array(
	array('cell one', 'cell two', 'cell three'),
	array('cell four', 'cell five', 'cell six'),
	array('cell seven', 'cell eight', 'cell nine')
);

// make sure your web server has permission to write to the folder
$file = new CsvWriter(dirname(__FILE__).'/../tests/data/file.csv');
foreach ($data as $line) {
	$file->addLine($line);
}
