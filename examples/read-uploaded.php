<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head> 
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
</head>
<body>
<?php require_once('../csv.php'); ?>
<?php if (isset($_FILES['csv_file'])) : ?>
	<?php $lines = new CsvReader($_FILES['csv_file']['tmp_name']); ?>
	<table>
	<?php foreach ($lines as $line_number => $values) : ?>
		<tr>
		<?php foreach ($values as $value) : ?>
		<?php if ($line_number == 0) : ?>
			<th><?= $value ?></th>
		<?php else : ?>
			<td><?= $value ?></td>
		<?php endif ?>
		<?php endforeach ?>
		</tr>
	<?php endforeach ?>
	</table>
<?php else : ?>
	<h1>Upload a csv file</h1>
	<form action="" method="post" enctype="multipart/form-data">
	<p>
		<label for="csv_file_id">File</label>
		<input type="file" name="csv_file" id="csv_file_id"/>
	</p>
	<p>
		<input type="submit"/>
	</p>
	</form>
<?php endif ?>
</body>
</html>
