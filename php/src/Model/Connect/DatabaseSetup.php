<?php namespace Stader\Model\Connect ;

use \Stader\Model\Connect\{ConnectPDO,ConnectXML} ;
use \Stader\Model\Traits\Settings ;

class DatabaseSetup
{
    /* private static $connect  = new IDbDriver() ; */
    protected static $connect = [] ;

    function __construct( $dbType )
    {   // echo 'class DatabaseSetup __construct' . \PHP_EOL ;
        // echo $dbType . \PHP_EOL ;

        $this->getSettings() ;
        if ( ! isset( self::$connect[ self::$iniSettings[$dbType]['dbname'] ] ) ) 
        {   //  echo "switch ( ". self::$iniSettings[$dbType]['dbname'] ." )" . \PHP_EOL ;
            //  print_r( [ 'dbType' => $dbType ] ) ;

            $dbMethod = self::$iniSettings[$dbType]['method'] ;
            switch ( $dbMethod )
            {
                case "cryptdata" : self::$connect[ self::$iniSettings[$dbType]['dbname'] ] = new ConnectPDO( $dbType ) ; break ;
                case "mysql"     : self::$connect[ self::$iniSettings[$dbType]['dbname'] ] = new ConnectPDO( $dbType ) ; break ;
                case "pgsql"     : self::$connect[ self::$iniSettings[$dbType]['dbname'] ] = new ConnectPDO( $dbType ) ; break ;
                case "sqlite"    : self::$connect[ self::$iniSettings[$dbType]['dbname'] ] = new ConnectPDO( $dbType ) ; break ;
                case "xml"       : self::$connect[ self::$iniSettings[$dbType]['dbname'] ] = new ConnectXML( $dbType ) ; break ;
                default: throw new \Exception() ;
            }   //  echo self::$connect[ self::$iniSettings[$dbType]['dbname'] ]->getType() . PHP_EOL ;
                //  print_r( self::$connect ) ;
        }   //  print_r( ['after',self::$connect[ self::$iniSettings[$dbType]['dbname'] ]->getConn()] ) ;

        //  $this->checkConnection( $dbType ) ;
    }

    use Settings ;

    public function getDBH( $dbType )
        { return self::$connect[ self::$iniSettings[$dbType]['dbname'] ]->getConn() ; }

/*
    function __destruct()
    {
        self::$connect = null ;
    }
 */

    private function checkConnection( $dbType )
    {
        $dbh = self::$connect[ self::$iniSettings[$dbType]['dbname'] ]->getConn() ;

        $sql[0]  = 'select *  ' ;
        $sql[0] .= 'from information_schema.tables ' ;
        $sql[0] .= 'where   table_schema = "'. self::$iniSettings[$dbType]['dbname'] .'"  ' ;

        $stmt[0] = $dbh->prepare( $sql[0] ) ;
        $stmt[0]->execute() ;

        $values = $stmt[0]->fetchAll( \PDO::FETCH_ASSOC ) ;

        print_r( $values ) ;
    }
}

?>
