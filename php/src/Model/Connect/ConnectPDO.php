<?php namespace Stader\Model\Connect ;

use \Stader\Model\Interfaces\DbDriver ;

class ConnectPDO extends DbDriver
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
    function __construct( $dbType )
    {   // echo 'class ConnectPDO extends DbDriver __construct' . \PHP_EOL ;
        // print_r( ['before',self::$conn,self::$type] ) ;

        if ( ! self::$conn ) 
        {
            $iniSettings =  parse_ini_file ( dirname( __file__ , 4 ) . "/settings/connect.ini" , true ) ;
//             print_r( $iniSettings ) ;

            self::$type = $iniSettings[$dbType]['method'] ;

            $connStr  = $iniSettings[$dbType]['pdo'] ;
            $connStr .= ':host=' . $iniSettings[$dbType]['host'] ;
            if ( $iniSettings[$dbType]['host'] !== 'localhost')
                $connStr .= ';port=' . $iniSettings[$dbType]['port'] ;
            $connStr .= ';dbname=' . $iniSettings[$dbType]['dbname'] ;
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
                self::$conn = new \PDO( $connStr , $iniSettings[$dbType]['user'] , $iniSettings[$dbType]['pass'] ) ;
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
