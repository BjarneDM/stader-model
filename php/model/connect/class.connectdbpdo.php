<?php namespace stader\model ;

require_once( dirname( __file__ , 2 ) . '/interfaces/interface.dbdriver.php' ) ;

class ConnectDbPDO extends DbDriver
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
    {   // echo 'class ConnectDbPDO extends DbDriver __construct' . \PHP_EOL ;
        // print_r( ['before',self::$conn,self::$type] ) ;

        if ( ! self::$conn ) 
        {
            self::$type = $type ;

            $iniSettings =  parse_ini_file ( dirname( __file__ , 3 ) . "/settings/connect.ini" , true ) ;
//             print_r( $iniSettings ) ;

            $connStr  = $iniSettings[$type]['pdo'] ;
            $connStr .= ':host=' . $iniSettings[$type]['host'] ;
            if ( $iniSettings[$type]['host'] !== 'localhost')
                $connStr .= ';port=' . $iniSettings[$type]['port'] ;
            $connStr .= ';dbname=' . $iniSettings[$type]['dbname'] ;
            if ( $type === 'mysql')
                $connStr .= ';charset=utf8mb4' ;

            /*
            print_r( 
            [
                $connStr , 
                $iniSettings[$type]['user'.$level] , 
                $iniSettings[$type]['pass'.$level] 
            ] ) ;
            */

            try
            {
                self::$conn = new \PDO( $connStr , $iniSettings[$type]['user'.$level] , $iniSettings[$type]['pass'.$level] ) ;
                self::$conn->setAttribute
                (
                    \PDO::ATTR_ERRMODE,
                    \PDO::ERRMODE_EXCEPTION
                ) ;
            }
            catch( PDOException $e )
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
