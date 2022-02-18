<?php namespace Stader\Model\Connect ;

use \Stader\Model\Connect\{ConnectPDO,ConnectXML} ;
use \Stader\Model\Traits\Settings ;

class DatabaseSetup
{
    /* private static $connect  = new IDbDriver() ; */
    protected static $connect ;
    private   static $dbType = 'data' ;

    /*
     */
    function __construct()
    {   // echo 'class Setup __construct' . \PHP_EOL ;

        $this->getSettings() ;

        if ( ! self::$connect ) 
        {
            $dbMethod = self::$iniSettings[self::$dbType]['method'] ;
            switch ( $dbMethod )
            {
                case "mysql"    : self::$connect = new ConnectPDO( self::$dbType ) ; break ;
                case "pgsql"    : self::$connect = new ConnectPDO( self::$dbType ) ; break ;
                case "sqlite"   : self::$connect = new ConnectPDO( self::$dbType ) ; break ;
                case "xml"      : self::$connect = new ConnectXML( self::$dbType ) ; break ;
                default: throw new \Exception() ;
            }   //  echo self::$connect->getType() . PHP_EOL ;
        }   //  print_r( ['after',self::$connect->getConn()] ) ;

        //  $this->checkConnection() ;
    }

    use Settings ;

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
