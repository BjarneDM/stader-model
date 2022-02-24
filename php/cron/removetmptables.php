<?php   namespace Stader\Cron ;

   $include_paths[] = dirname( __DIR__ ) ;
// $include_paths[] =  '/Volumes/Bjarne/Sites/info/mathiesen/zbc/cdn/php' ;
// $include_paths[] =  '.' ;
// $include_paths[] =  '/Volumes/Bjarne/Sites/info/mathiesen/cdn/_/php' ;
set_include_path( implode( ':' , $include_paths ) ) ;

/*
 *  dropper alle "tmp_%" tabeller,
 *  der er ældre end 5 minutter
 *
 *  dette program skal køres hvert minut
 */

/*
 *  dette var nødvendigt m/ den oprindelige struktur,
 *  hvor \User var én enkelt tabel, der var fuldt ud krypteret
 *  & defor blev dekrypteret & læst ind som en midlertidig table i RAM
 *  Problemet var, at hvis en PHP process crashede 
 *  ( hvilket de tit gjorde under udvikling & test)
 *  forblev disse midlertidiger tabeller i hukommelsen,
 *  hvorfor der jævnligt skulle ryddes op i disse.
 */

/*
 *  setup
 */

require_once( 'classloader.php' ) ;

use \Stader\Model\Connect\DatabaseSetup

// $setup = new Setup() ;
// $dbh = $setup->getDBH() ;
$dbh = ( new DatabaseSetup( 'data' ) )->getDBH() ;

$sql = [] ;
$stmt = [] ;

$NOW = new \DateTime() ;

/*
 *  main
 */

/*
select table_name , create_time , update_time , check_time
from information_schema.tables  
where   table_schema = "staderdata"       
    and table_name like "tmp_%"
;
 */

$sql[0]  = 'select table_name , create_time , update_time , check_time ' ;
$sql[0] .= 'from information_schema.tables  ' ;
$sql[0] .= 'where   table_schema = "staderdata" ' ;
$sql[0] .= '    and table_name like "tmp_%" ' ;

$stmt[0] = $dbh->prepare( $sql[0] ) ;
$stmt[0]->execute() ;

while ( $values = $stmt[0]->fetch( \PDO::FETCH_ASSOC ) )
{
    // var_dump( $values ) ;
    $values['CREATE_TIME'] = \DateTime::createFromFormat( 'Y-m-d H:i:s' , $values['CREATE_TIME'] ) ;

    if ( abs( $NOW->format( 'U' ) - $values['CREATE_TIME']->format( 'U' ) )  > ( 5*60 ) )
    {
        // echo "!!! dropper tabellen : " . $values['TABLE_NAME'] . "!!!" . \PHP_EOL ;
        $sql[1]  = 'drop table ' . $values['TABLE_NAME'] . ' ' ;
        $stmt[1] = $dbh->prepare( $sql[1] ) ;
        $stmt[1]->execute() ;
    }
}

/*
 *  cleanup
 */

foreach( $stmt as $key => $value ) 
    $stmt[$key] = null ;
    unset( $key , $value ) ;
$dbh = null ;

?>
