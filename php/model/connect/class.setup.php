<?php namespace stader\model ;

class Setup
{
    /* private static $connect  = new IDbDriver() ; */
    protected static $connect ;
    protected static $iniSettings ; 

    /*
     */
    function __construct( $type )
    {   // echo 'class Setup __construct' . \PHP_EOL ;

        if ( ! self::$connect ) 
        {
            self::$iniSettings = parse_ini_file( dirname( __file__ , 3 ) . '/settings/connect.ini' , true ) ;
            $dbMethod = self::$iniSettings[$type]['method'] ;
            switch ( $dbMethod )
            {
                case "mysql"    : self::$connect = new ConnectPDO( $type ) ; break ;
                case "pgsql"    : self::$connect = new ConnectPDO( $type ) ; break ;
                case "sqlite"   : self::$connect = new ConnectPDO( $type ) ; break ;
                case "xml"      : self::$connect = new ConnectXML()         ; break ;
                default: throw new \Exception() ;
            } // echo $this::$connect->getType() . PHP_EOL ;
        }   // print_r( ['after',self::$connect->getConn()] ) ;

        // $this->checkConnection() ;
    }

    public function getDBH()
        { return self::$connect->getConn() ; }

    /*
     *  der må !!!IKKE!!! være en __destruct i denne class
     *  det fucker de statiske variable fuldstændigt op
     */
/*
    function __destruct()
    {
        self::$connect = null ;
    }
 */

    private function checkConnection()
    {
        $dbh = self::$connect->getConn() ;

        $sql[0]  = 'select *  ' ;
        $sql[0] .= 'from information_schema.tables ' ;
        $sql[0] .= 'where   table_schema = "stader"  ' ;

        $stmt[0] = $dbh->prepare( $sql[0] ) ;
        $stmt[0]->execute() ;

        $values = $stmt[0]->fetchAll( \PDO::FETCH_ASSOC ) ;

        print_r( $values ) ;
    }
}

?>
