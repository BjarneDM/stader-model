<?php namespace Stader\Model\Connect ;

use \Stader\Model\Interfaces\DbDriver ;

class LogConnectPDO extends DbDriver
{
    protected static $conn = null ;
    protected static $type = null ;
    private   static $dbType = 'logs' ;
 
    /**
    * This method returns the connection object.
    * If it has not been yet created, this method
    * instantiates it based on 
    * variables defined in connect.ini
    * @return PDO the connection object
    */
    function __construct()
    {   // echo 'class ConnectPDO extends DbDriver __construct' . \PHP_EOL ;
        // print_r( ['before',self::$conn,self::$type] ) ;

        if ( ! self::$conn ) 
        {
            $iniSettings =  parse_ini_file ( dirname( __file__ , 4 ) . "/settings/connect.ini" , true ) ;
//             print_r( $iniSettings ) ;

            self::$type = $iniSettings[self::$dbType]['method'] ;

            $connStr  = $iniSettings[self::$dbType]['pdo'] ;
            $connStr .= ':host=' . $iniSettings[self::$dbType]['host'] ;
            if ( $iniSettings[self::$dbType]['host'] !== 'localhost')
                $connStr .= ';port=' . $iniSettings[self::$dbType]['port'] ;
            $connStr .= ';dbname=' . $iniSettings[self::$dbType]['dbname'] ;
            if ( self::$type === 'mysql')
                $connStr .= ';charset=utf8mb4' ;

            /*
            print_r( 
            [
                $connStr , 
                $iniSettings[$type]['user'] , 
                $iniSettings[$type]['pass'] 
            ] ) ;
            */

            try
            {
                self::$conn = new \PDO( $connStr , $iniSettings[self::$dbType]['user'] , $iniSettings[self::$dbType]['pass'] ) ;
                self::$conn->setAttribute
                (
                    \PDO::ATTR_ERRMODE,
                    \PDO::ERRMODE_EXCEPTION
                ) ;
            }
            catch( \PDOException $e )
            {
                showHeader('Error') ;
                showError("Sorry, an error has occurred. Please
                try your request later\n" . $e->getMessage()) ;
            }
        }   // print_r( ['after',self::$conn,self::$type] ) ;
    }


    public function getConn() { return self::$conn ; }
    
    public function getType() { return self::$type ; }

    function __destruct() { $conn = null ; }
}

?>
