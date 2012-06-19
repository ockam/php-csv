PHP-CSV
=======

Goal
----

The implementation in PHP of the CSV related function does not follow the
standard (RFC 4180). It was causing problems when we tried to import/export
data from/to Microsoft Office Excel.

We tried various ways to import/export large datasets from/to Excel,
but the existing solutions that parsed Excel files always had to load the
entire spreadsheet in memory. That caused the script to use excessive amounts
of memory for not so large datasets (e.g. With PHPExcel, 
~1000 rows used ~64MB of memory).

We decided to use CSV as it is easily parsable and writable on the fly.


Presentation
------------

php-csv is an open source (MIT license) library to import/export large datasets
in the CSV format. It currently assumes the CSV files are ISO-8859-1 encoded
and that the wanted php arrays are in UTF-8.


Unit Testing
------------

We used SimpleTest for the unit tests. If you want to run the tests, you'll
have to download SimpleTest and put its folder in the same folder as csv.php.


Bug Reporting and Patches
-------------------------

Please use the github repository located at http://github.com/ockam/php-csv if
you find any bugs or would like to contribute.


Usage
-----

Have a look at the "examples" folder. Or look directly into the source code.

