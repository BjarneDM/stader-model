<?php namespace Stader\Model\Connect ;

use \Stader\Model\Interfaces\DbDriver ;

class ConnectXML extends DbDriver
{
    /**
    * This method returns the connection object.
    * If it has not been yet created, this method
    * instantiates it based on 
    * variables defined in connect.ini
    * @return PDO the connection object
    */
    function __construct( $type )
    {   // echo 'class ConnectXML extends DbDriver __construct' . \PHP_EOL ;
        if ( ! self::$conn ) 
        {
            self::$type = $type ;

            $iniSettings =  parse_ini_file ( dirname( __file__ , 3 ) . '/settings/connect.ini' , true ) ;

            try
            {
            }
            catch( \Exception $e )
            {
                showHeader('Error') ;
                showError("Sorry, an error has occurred. 
                           Please try your request later\n" 
                           . $e->getMessage()) ;
            }
        }
        return self::$conn ;
    }

}

?>
