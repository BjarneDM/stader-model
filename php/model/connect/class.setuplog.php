<?php namespace stader\model ;

require_once( __dir__ . '/class.connectlogpdo.php' ) ;

class SetupLog
{
    /* private static $connect  = new IDbDriver() ; */
    protected static $connect ;
    protected static $iniSettings ; 

    /*
     *  $args[0] : connection type
     */
    function __construct()
    {   // echo 'class Setup __construct' . \PHP_EOL ;

        if ( ! self::$connect ) 
        {
            self::$iniSettings = parse_ini_file( dirname( __file__ , 3 ) . '/settings/connect.ini' , true ) ;
            $dbMethod = self::$iniSettings['connection']['method'] ;
            switch ( $dbMethod )
            {
                case "mysql"    : self::$connect = new ConnectLogPDO( $dbMethod ) ; break ;
                case "pgsql"    : self::$connect = new ConnectLogPDO( $dbMethod ) ; break ;
                case "sqlite"   : self::$connect = new ConnectLogPDO( $dbMethod ) ; break ;
                case "xml"      : self::$connect = new ConnectLogXML()            ; break ;
                default: throw new \Exception() ;
            } // echo $this::$connect::getType() . PHP_EOL ;
        }   // print_r( ['after',self::$connect::getConn()] ) ;

        // $this->checkConnection() ;
    }

    function __destruct()
    {
        $this::$connect = null ;
    }

    private function checkConnection()
    {
        $dbh = self::$connect->getConn() ;

        $sql[0]  = 'select *  ' ;
        $sql[0] .= 'from information_schema.tables ' ;
        $sql[0] .= 'where   table_schema = "logs"  ' ;

        $stmt[0] = $dbh->prepare( $sql[0] ) ;
        $stmt[0]->execute() ;

        $values = $stmt[0]->fetchAll( \PDO::FETCH_ASSOC ) ;

        print_r( $values ) ;
    }

}

?>
