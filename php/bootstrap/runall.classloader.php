<?php   namespace stader\bootstrap ;


require_once( dirname( __file__ , 2 ) . '/model/class.classloader.php' ) ;

chdir( dirname( __file__ ) ) ;
$phpFiles = glob( '[0-9][0-9].*.php' ) ;
sort( $phpFiles ) ;
foreach ( $phpFiles as $phpFile )
    require_once( dirname( __file__ ) . '/' . $phpFile ) ;

?>
