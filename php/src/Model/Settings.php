<?php namespace Stader\Model ;

class Settings
{
    private static $iniSettings ;
    
    function __construct()
    {
        if ( ! self::$iniSettings ) 
        {
            self::$iniSettings = 
                parse_ini_file( 
                    dirname( __file__ , 3 ) . '/settings/connect.ini' , 
                    true 
                ) ;
        }

    // print_r( self::$iniSettings) ;

    }

    public function getSetting( ...$args ): string | int | bool
    {
        switch ( count( $args ) )
        {
            case 1 :
                return self::$iniSettings[$args[0]] ;
                break;
            case 2 :
                return self::$iniSettings[$args[0]][$args[1]] ;
               break;
            default :
                throw new \Exception( count( $args ) . " : forkert antal parametre [1,2]" ) ;
                break ;
        }
    }

}

?>
