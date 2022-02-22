<?php namespace Stader\Model\Connect ;

use \Stader\Model\Connect\{ConnectPDO,ConnectXML} ;
use \Stader\Model\Traits\Settings ;

class DatabaseSetup
{
    /* private static $connect  = new IDbDriver() ; */
    protected $connect ;

    function __construct( $dbType )
    {   // echo 'class Setup __construct' . \PHP_EOL ;

        $this->getSettings() ;

        if ( ! $this->connect ) 
        {
            $dbMethod = self::$iniSettings[$dbType]['method'] ;
            switch ( $dbMethod )
            {
                case "cryptdata" : $this->connect = new ConnectPDO( $dbType ) ; break ;
                case "mysql"     : $this->connect = new ConnectPDO( $dbType ) ; break ;
                case "pgsql"     : $this->connect = new ConnectPDO( $dbType ) ; break ;
                case "sqlite"    : $this->connect = new ConnectPDO( $dbType ) ; break ;
                case "xml"       : $this->connect = new ConnectXML( $dbType ) ; break ;
                default: throw new \Exception() ;
            }   //  echo $this->connect->getType() . PHP_EOL ;
        }   //  print_r( ['after',$this->connect->getConn()] ) ;

        //  $this->checkConnection() ;
    }

    use Settings ;

    public function getDBH()
        { return $this->connect->getConn() ; }

    function __destruct()
    {
        $this->connect = null ;
    }

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
