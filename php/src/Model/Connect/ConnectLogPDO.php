<?php namespace Stader\Model\Connect ;

use \Stader\Model\Interfaces\DbDriver ;

class ConnectLogPDO extends DbDriver
{
    protected static $conn = null ;
    protected static $type = null ;

    /**
    * This method returns the connection object.
    * If it has not been yet created, this method
    * instantiates it based on 
    * variables defined in connect.ini
    * @return PDO the connection object
    */
    function __construct( $type , $level = null )
    {   // echo 'class ConnectLogPDO extends DbDriver __construct' . \PHP_EOL ;

        if ( ! self::$conn ) 
        {
            self::$type = $type ;

            $iniSettings =  parse_ini_file ( dirname( __file__ , 3 ) . "/settings/connect.ini" , true ) ;
            // print_r( $iniSettings ) ;

            $connStr  = $iniSettings['log'.$type]['pdo'] ;
            $connStr .= ':host=' . $iniSettings['log'.$type]['host'] ;
            if ( $iniSettings[$type]['host'] !== 'localhost')
                $connStr .= ';port=' . $iniSettings['log'.$type]['port'] ;
            $connStr .= ';dbname=' . $iniSettings['log'.$type]['dbname'] ;
            if ( $type === 'mysql')
                $connStr .= ';charset=utf8mb4' ;

            /*
            print_r( 
            [
                $connStr , 
                $iniSettings['log'.$type]['user'.$level] , 
                $iniSettings['log'.$type]['pass'.$level] 
            ] ) ;
            */

            try
            {
                self::$conn = new \PDO( $connStr , $iniSettings['log'.$type]['user'.$level] , $iniSettings['log'.$type]['pass'.$level] ) ;
                self::$conn->setAttribute
                (
                    \PDO::ATTR_ERRMODE,
                    \PDO::ERRMODE_EXCEPTION
                ) ;
            }
            catch( \PDOException $e )
            {
                echo $e->getMessage() . \PHP_EOL ;
                showHeader('Error') ;
                showError("Sorry, an error has occurred. Please
                try your request later\n" . $e->getMessage()) ;
            }
        }
    }

    public function getConn() { return self::$conn ; }
    
    public function getType() { return self::$type ; }

    function __destruct() { $conn = null ; }
}

?>
