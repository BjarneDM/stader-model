<?php namespace Stader\Model\Connect ;

use \Stader\Model\Interfaces\DbDriver ;
use \Stader\Model\Traits\Settings ;

class DataConnectPDO extends DbDriver
{
    protected static $conn        = null ;
    protected static $type        = null ;
    private   static $dbType      = 'data' ;
 
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

        if ( ! self::$iniSettings ) $this->getSettings() ;

        if ( ! self::$conn ) 
        {

            self::$type = self::$iniSettings[self::$dbType]['method'] ;

            $connStr  = self::$iniSettings[self::$dbType]['pdo'] ;
            $connStr .= ':host=' . self::$iniSettings[self::$dbType]['host'] ;
            if ( self::$iniSettings[self::$dbType]['host'] !== 'localhost')
                $connStr .= ';port=' . self::$iniSettings[self::$dbType]['port'] ;
            $connStr .= ';dbname=' . self::$iniSettings[self::$dbType]['dbname'] ;
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
                self::$conn = new \PDO( $connStr , self::$iniSettings[self::$dbType]['user'] , self::$iniSettings[self::$dbType]['pass'] ) ;
                self::$conn->setAttribute
                (
                    \PDO::ATTR_ERRMODE,
                    \PDO::ERRMODE_EXCEPTION
                ) ;
            }
            catch( \PDOException $e )
            {   echo $e->getMessage() . \PHP_EOL ;
                // showHeader('Error') ;
                // showError("Sorry, an error has occurred. Please
                // try your request later\n" . $e->getMessage()) ;
            }
        }   // print_r( ['after',self::$conn,self::$type] ) ;
    }

    use Settings ;

    public function getConn() { return self::$conn ; }
    
    public function getType() { return self::$type ; }

    function __destruct() { $conn = null ; }
}

?>
