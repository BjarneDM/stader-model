<?php namespace Stader\Model\Connect ;

use \Stader\Model\Connect\{DataConnectPDO,DataConnectXML} ;

class DatabaseSetup
{
    /* private static $connect  = new IDbDriver() ; */
    protected static $connect ;
    protected static $iniSettings ;
    private   static $dbType = 'data' ;

    /*
     */
    function __construct()
    {   // echo 'class Setup __construct' . \PHP_EOL ;

        if ( ! self::$connect ) 
        {
            self::$iniSettings = parse_ini_file( dirname( __file__ , 4 ) . '/settings/connect.ini' , true ) ;
            $dbMethod = self::$iniSettings[self::$dbType]['method'] ;
            switch ( $dbMethod )
            {
                case "mysql"    : self::$connect = new DataConnectPDO() ; break ;
                case "pgsql"    : self::$connect = new DataConnectPDO() ; break ;
                case "sqlite"   : self::$connect = new DataConnectPDO() ; break ;
                case "xml"      : self::$connect = new DataConnectXML() ; break ;
                default: throw new \Exception() ;
            }   //  echo $this::$connect->getType() . PHP_EOL ;
        }   //  print_r( ['after',self::$connect->getConn()] ) ;

        //  $this->checkConnection() ;
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
