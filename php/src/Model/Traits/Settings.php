<?php namespace Stader\Model\Traits ;

trait Settings
{
    protected static $iniSettings ;

    /*
     */
    function getSettings()
    {   // echo 'class Setup __construct' . \PHP_EOL ;

        if ( ! self::$iniSettings ) 
        {
            self::$iniSettings = parse_ini_file( dirname( __file__ , 4 ) . '/settings/connect.ini' , true ) ;
        }
     }

}

?>
