<?php
require_once('../csv.php');
$data = array(
	array('cell one', 'cell two', 'cell three'),
	array('cell four', 'cell five', 'cell six'),
	array('cell seven', 'cell eight', 'cell nine')
);

header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename="file.csv"');
header('Cache-Control: max-age=0');
$file = new CsvWriter('php://output');
foreach ($data as $line) {
	$file->addLine($line);
}
