<?php   namespace stader\bootstrap ;

require_once( dirname( __file__ , 2 ) . '/model/class.classloader.php' ) ;

chdir( __dir__ ) ;
$phpFiles = glob( '[0-9][0-9].*.php' ) ;
sort( $phpFiles ) ;
foreach ( $phpFiles as $phpFile )
    require_once( __dir__ . '/' . $phpFile ) ;

?>
