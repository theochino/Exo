<?php 
// Applicant : Theo Chino

/*
	1. Retrieving the data from the API (you can assume this will be triggered by a cron job)
	2. Parsing the data
	3. Storing the data in your MySQL table
	4. Given an amount of a foreign currency, convert it into the equivalent in US dollars. 
			For example:
				input: 'JPY 5000'
				output: 'USD 65.63'
	5. Given an array of amounts in foreign currencies, return an array of US equivalent amounts in the same order. 
			For example:
				input: array( 'JPY 5000', 'CZK 62.5' )
				output: array( 'USD 65.63', 'USD 3.27' )
*/

/* Using strings to store the rate because don't feel like dealing with the floats errors */

// Retreive the info
$CurConvURL = "https://wikitech.wikimedia.org/wiki/Fundraising/tech/Currency_conversion_sample?ctype=text/xml&action=raw";

$InputArray = array( 'JPY 5000', 'CZK 62.5', 'CHF 2393' );
$OuputArray = array();

$CurRates = file_get_contents($CurConvURL);
$parser = xml_parser_create();
xml_parse_into_struct($parser, $CurRates, $vals, $index);
xml_parser_free($parser);

// Just to have it there.
$Currency["USD"] = "1";

if ( ! empty ($index["CURRENCY"])) {
	foreach ($index["CURRENCY"] as $var => $localindex) {
		$Currency[ $vals[$localindex]["value"] ] = $vals[$index["RATE"][$var]]["value"];
	}	
}

/*
  Store $Currency to DB

  Depending on the size and the importance of keepign historical data
  I would either keep an historical DB of the convertion rate 
  and would keep several tables such as the Currency, Country, 
  Source of the data, etc ....
  but for this exercise, the rate is the rate 
 	
	CREATE TABLE RateTable (
		RateTable_Currency char(3),
		RateTable_Rate decimal (10,6)
	);	
	
  Usually I have all my DB in a diff class and just 
  call the function. I keep those classes outside the reach
  of the HTTPD server.

  I also use PDO prepared statements to make sure that I don't get hit by
  SQL injections.
  
  For today's example, I would either build a single SQL statement with multiple values.

  For today exercise, I would just delete the whole table and repopulate
 
  $SQL_Statement = "DELETE FROM RateTable";
  $SQL_Statement = "INSERT INTO RateTable (";
  foreach () {
    $SQL_Statement .= "(VALUES, VALUES) ";
    if ( ! LastLoop ) {
     $SQL_Statement .= ",";
    }
  }
 $SQL_Statement = ")";
*/

// Conversion of the currency.

if ( ! empty ($InputArray)) {
	foreach ($InputArray as $InputVar) {
		if ( ! empty ($InputVar)) {
			
			$InputCurrency = preg_split ("/ /", $InputVar);
	
			// This check for a conversion, if the 3 letter code is invalid.
			// Just stop with an error, don't do any processing.
			if ( empty ( $InputCurrency[0] ) && $InputCurrency[1] < 0) {
				IGotAnError();
				exit();
			}
			
			// Conversion happens here.
			if ( empty ($ConvertedCurrency = ($InputCurrency[1] * $Currency[$InputCurrency[0]]))) {
				IGotAnError();
				exit();
			}

			$OuputArray[] = "USD " . $ConvertedCurrency;
		}
	}
}

print "Converted Currencies:\n";
print_r($OutputArray);

if ( ! empty ($OutputArray)) {
	foreach ($OutputArray as $ConverCurrency) {
		if ( ! empty ($ConverCurrency)) {
			print "\t" . $ConverCurrency . "\n";
		}
	}
}

function IGotAnError() {
	// Should be sending to STDERR but not going to do so today.
	// Would be sending it to the error Class for processing.
	print "There is an error somewhere. Aborting\n";
}

?>