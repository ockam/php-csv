<?php
require_once('../csv.php');
$lines = new CsvReader(dirname(__FILE__).'/../tests/data/two-lines.csv');
echo "<table>\n";
foreach ($lines as $line_number => $values) {
	echo '<tr>';
	echo '<td>'.$line_number.'</td>';
	foreach ($values as $value) {
		echo '<td>'.$value."</td>";
	}
	echo "</tr>\n";
}
echo '</table>';
