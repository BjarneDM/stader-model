<?php

error_reporting(0) ;

// Report all PHP errors 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// logging
// https://www.loggly.com/ultimate-guide/php-logging-basics/
// ini_set( 'log_errors', 1 ) ;
// ini_set('display_errors', 0);
// ini_set('display_startup_errors', 0);
// error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT);

setlocale( LC_CTYPE , "C" ) ;
setlocale( LC_ALL , "da_DK" ) ;
date_default_timezone_set ( "Europe/Copenhagen" ) ;
/*
$dateFormat1 = new IntlDateFormatter(
    "da_DK",
    IntlDateFormatter::FULL, 
    IntlDateFormatter::NONE,
    "Europe/Copenhagen",
    IntlDateFormatter::GREGORIAN
    ) ;
$dateFormat1->setPattern("ccc dd'/'MM'-'yyyy") ;

$dateFormat2 = new IntlDateFormatter(
    "da_DK",
    IntlDateFormatter::FULL, 
    IntlDateFormatter::NONE,
    "Europe/Copenhagen",
    IntlDateFormatter::GREGORIAN
    ) ;
$dateFormat2->setPattern("cccc dd'/'MM'-'yyyy HH':'mm':'ss") ;
*/
?>
