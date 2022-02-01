<?php   namespace Stader\Bootstrap ;

chdir( __dir__ ) ;
$phpFiles = glob( '[0-9][0-9].*.php' ) ;
sort( $phpFiles ) ;
foreach ( $phpFiles as $phpFile )
    require_once( __dir__ . '/' . $phpFile ) ;

?>
