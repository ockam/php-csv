<?php
error_reporting(E_ALL ^ E_DEPRECATED);
require_once(dirname(__FILE__) . '/../simpletest/autorun.php');
require_once(dirname(__FILE__) . '/../csv.php');

class CsvTest extends UnitTestCase {

	function testParseString_NormalValues() {
		$array = Csv::parseString('string1,string2,string3,string4');
		$expected = array('string1', 'string2', 'string3', 'string4');
		$this->assertEqual($array, $expected);
	}

	function testParseString_OneQuoted() {
		$array = Csv::parseString('string1,string2,string3,"string4"');
		$expected = array('string1', 'string2', 'string3', 'string4');
		$this->assertEqual($array, $expected);
	}

	function testParseString_OneQuotedWithQuotesInside() {
		$array = Csv::parseString('string1,string2,string3,"""string4"""');
		$expected = array('string1', 'string2', 'string3', '"string4"');
		$this->assertEqual($array, $expected);
	}

	function testParseString_OneQuotedWithCommaInside() {
		$array = Csv::parseString('string1,string2,string3,"string4,string5"');
		$expected = array('string1', 'string2', 'string3', 'string4,string5');
		$this->assertEqual($array, $expected);
	}

	function testParseString_OneQuotedWithCommaAndQuotesInside() {
		$array = Csv::parseString('string1,string2,string3,"""string4"",string5"');
		$expected = array('string1', 'string2', 'string3', '"string4",string5');
		$this->assertEqual($array, $expected);
	}

	function testParseString_SomeQuotedString() {
		$array = Csv::parseString('string1,string2,string3,"""string4","string5"');
		$expected = array('string1', 'string2', 'string3', '"string4', 'string5');
		$this->assertEqual($array, $expected);
	}

	function testParseString_SomeQuotedStringFromExcel() {
		$array = Csv::parseString('One,Two words,"One ""quoted""","Single ""quote"');
		$expected = array('One', 'Two words', 'One "quoted"', 'Single "quote');
		$this->assertEqual($array, $expected);
	}

	function testEscapeString_NormalString() {
		$result = Csv::escapeString('a string');
		$expected = 'a string';
		$this->assertEqual($result, $expected);
	}

	function testEscapeString_StringWithComa() {
		$result = Csv::escapeString('a string, with comma');
		$expected = '"a string, with comma"';
		$this->assertEqual($result, $expected);
	}

	function testEscapeString_StringWithQuote() {
		$result = Csv::escapeString('a string" with quote');
		$expected = '"a string"" with quote"';
		$this->assertEqual($result, $expected);
	}

	function testEscapeString_StringWithQuoteAndComma() {
		$result = Csv::escapeString('a string", with quote');
		$expected = '"a string"", with quote"';
		$this->assertEqual($result, $expected);
	}

	function testEscapeString_StringWithInnerLineBreaks() {
		$result = Csv::escapeString("A String\r\nWith Inner\rLine\nBreaks");
		$expected = "\"A String\r\nWith Inner\rLine\nBreaks\"";
		$this->assertEqual($result, $expected);
	}

	function testhasEndQuote_NoQuoteString() {
		$result = Csv::_hasEndQuote('a string');
		$this->assertFalse($result);
	}

	function testhasEndQuote_EndWithSingleQuote() {
		$result = Csv::_hasEndQuote('a string"');
		$this->assertTrue($result);
	}

	function testHasEndQuote_EndWithOneEscapedQuote() {
		$result = Csv::_hasEndQuote('a string""');
		$this->assertFalse($result);
	}

	function testHasEndQuote_EndWithOneEscapedQuoteAndOneNotEscaped() {
		$result = Csv::_hasEndQuote('a string"""');
		$this->assertTrue($result);
	}

	function testHasEndQuote_EmptyString() {
		$result = Csv::_hasEndQuote('');
		$this->assertFalse($result);
	}

	function testHasEndQuote_OnlyOneQuote() {
		$result = Csv::_hasEndQuote('"');
		$this->assertTrue($result);
	}

	function testHasEndQuote_OnlyOneEscapedQuote() {
		$result = Csv::_hasEndQuote('""');
		$this->assertFalse($result);
	}

	function testHasEndQuote_OneEscapedQuoteAndOneUnescaped() {
		$result = Csv::_hasEndQuote('"""');
		$this->assertTrue($result);
	}

	function testHasEndQuote_EndWithOneUnescapedQuoteWithQuotesInside() {
		$result = Csv::_hasEndQuote('a strin"g"');
		$this->assertTrue($result);
	}

	function testHasEndQuote_EndWithOneEscapedQuoteWithQuotesInside() {
		$result = Csv::_hasEndQuote('a strin"g""');
		$this->assertFalse($result);
	}
	
	function testDetectSeparator_ComaAndSemiColon() {
		$result = Csv::detectSeparator(dirname(__FILE__) .'/data/two-lines.csv');
		$this->assertEqual($result, ',');
		$result = Csv::detectSeparator(dirname(__FILE__) .'/data/semicolon-separator.csv');
		$this->assertEqual($result, ';');
	}
}

class CsvReaderTest extends UnitTestCase {

	function testCsvReader_OneLine() {
		$lines = new CsvReader(dirname(__FILE__) . '/data/one-line.csv');
		foreach ($lines as $line) {
			$expected = array('One', 'Two words', 'One "quoted"', 'Single "quote');
			$this->assertEqual($line, $expected);
		}
	}

	function testCsvReader_OneLineWithAccents() {
		$lines = new CsvReader(dirname(__FILE__) . '/data/one-line-with-accents.csv');
		foreach ($lines as $line) {
			$expected = array('One', 'Twô wördç', 'One "quoted"', 'Single "quote');
			//echo '<pre>';print_r($line);echo '</pre>';
			$this->assertEqual($line, $expected);
		}
	}

	function testCsvReader_TwoLines() {
		$lines = new CsvReader(dirname(__FILE__) . '/data/two-lines.csv');
		$values = array();
		foreach ($lines as $line) {
			$values[] = $line;
		}
		$expected = array(
			array('One', 'Two words', 'One "quoted"', 'Single "quote'),
			array('Line Number', '"Two "" is here"', 'Is', 'It fine?')
		);
		$this->assertEqual($values, $expected);
	}

	function testCsvReader_TwoLinesUnix() {
		$lines = new CsvReader(dirname(__FILE__) . '/data/two-lines-unix.csv');
		$values = array();
		foreach ($lines as $line) {
			$values[] = $line;
		}
		$expected = array(
			array('One', 'Two words', 'One "quoted"', 'Single "quote'),
			array('Line Number', '"Two "" is here"', 'Is', 'It fine?')
		);
		$this->assertEqual($values, $expected);
	}
	
	function testCsvReader_SemicolonSeparator() {
		$lines = new CsvReader(dirname(__FILE__) . '/data/semicolon-separator.csv', ';');
		$values = array();
		foreach ($lines as $line) {
			$values[] = $line;
		}
		$expected = array(
			array('One', 'Two words', 'One "quoted"', 'Single "quote'),
			array('Line Number', '"Two "" is here"', 'Is', 'It fine?')
		);
		$this->assertEqual($values, $expected);
	}
}

class CsvWriterTest extends UnitTestCase {

	function testCsvReader_OneLine() {
		$filename = dirname(__FILE__) . '/data/writer-one-line.csv';
		$csv = new CsvWriter($filename);
		$csv->addLine(array('One', 'Two words', 'One "quoted"', 'Single "quote'));
		$csv->close();
		$file = fopen(dirname(__FILE__) . '/data/writer-one-line.csv', 'r');
		$string = fgets($file);
		fclose($file);
		@unlink($filename);
		$expected = 'One,Two words,"One ""quoted""","Single ""quote"'."\r\n";
		$this->assertEqual($string, $expected);
	}

	function testCsvReader_OneLineWithAccents() {
		$filename = dirname(__FILE__) . '/data/writer-one-line.csv';
		$csv = new CsvWriter($filename);
		$csv->addLine(array('One', 'Twô wördç', 'One "quoted"', 'Single "quote'));
		$csv->close();
		$file = fopen(dirname(__FILE__) . '/data/writer-one-line.csv', 'r');
		$string = utf8_encode(fgets($file));
		fclose($file);
		@unlink($filename);
		$expected = 'One,Twô wördç,"One ""quoted""","Single ""quote"'."\r\n";
		$this->assertEqual($string, $expected);
	}

	function testCsvReader_TwoLines() {
		$filename = dirname(__FILE__) . '/data/writer-one-line.csv';
		$csv = new CsvWriter($filename);
		$csv->addLine(array('One', 'Two words', 'One "quoted"', 'Single "quote'));
		$csv->addLine(array('Line Number', '"Two "" is here"', 'Is', 'It fine?'));
		$csv->close();
		$file = fopen(dirname(__FILE__) . '/data/writer-one-line.csv', 'r');
		$string1 = utf8_encode(fgets($file));
		$string2 = utf8_encode(fgets($file));
		fclose($file);
		@unlink($filename);
		$expected1 = 'One,Two words,"One ""quoted""","Single ""quote"'."\r\n";
		$expected2 = 'Line Number,"""Two """" is here""",Is,It fine?'."\r\n";
		$this->assertEqual($string1, $expected1);
		$this->assertEqual($string2, $expected2);
	}
}
?>
