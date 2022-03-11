<?php namespace Stader\Model\Connect ;

use \Stader\Model\Connect\{ConnectPDO,ConnectXML} ;
use \Stader\Model\Settings ;

class DatabaseSetup
{
    // holder styr på forbindelserne t/ de enkelte DBer
    protected static $connect = [] ;
    protected static Settings $iniSettings ;

    function __construct( $dbType )
    {   // echo 'class DatabaseSetup __construct' . \PHP_EOL ;
        // echo $dbType . \PHP_EOL ;

        if ( ! isset( self::$iniSettings ) ) self::$iniSettings = new Settings() ;

        if ( ! isset( self::$connect[ self::$iniSettings->getSetting($dbType, 'dbname') ] ) ) 
        {   // echo "switch ( ". $this->iniSettings->getSetting($dbType, 'dbname') ." )" . \PHP_EOL ;
            // print_r( [ 'dbType' => $dbType ] ) ;

            $dbMethod = self::$iniSettings->getSetting( $dbType, 'method') ;
            switch ( $dbMethod )
            {
                case "cryptdata" : self::$connect[self::$iniSettings->getSetting($dbType, 'dbname') ] = new ConnectPDO( $dbType ) ; break ;
                case "mysql"     : self::$connect[self::$iniSettings->getSetting($dbType, 'dbname') ] = new ConnectPDO( $dbType ) ; break ;
                case "pgsql"     : self::$connect[self::$iniSettings->getSetting($dbType, 'dbname') ] = new ConnectPDO( $dbType ) ; break ;
                case "sqlite"    : self::$connect[self::$iniSettings->getSetting($dbType, 'dbname') ] = new ConnectPDO( $dbType ) ; break ;
                case "xml"       : self::$connect[self::$iniSettings->getSetting($dbType, 'dbname') ] = new ConnectXML( $dbType ) ; break ;
                default: throw new \Exception() ;
            }   // echo self::$connect[ self::$iniSettings->getSetting($dbType, 'dbname') ]->getType() . PHP_EOL ;
                // print_r( self::$connect ) ;
        }   // print_r( ['after',self::$connect[ self::$iniSettings->getSetting($dbType, 'dbname') ]->getConn()] ) ;

        // $this->checkConnection( $dbType ) ;
    }

    public function getDBH( $dbType )
        { return self::$connect[ self::$iniSettings->getSetting($dbType, 'dbname') ]->getConn() ; }

/*
    function __destruct()
    {
        self::$connect = null ;
    }
 */

    private function checkConnection( $dbType )
    {
        $dbh = self::$connect[ self::$iniSettings->getSetting($dbType, 'dbname') ]->getConn() ;

        $sql[0]  = 'select *  ' ;
        $sql[0] .= 'from information_schema.tables ' ;
        $sql[0] .= 'where   table_schema = "'. self::$iniSettings->getSetting($dbType, 'dbname') .'"  ' ;

        $stmt[0] = $dbh->prepare( $sql[0] ) ;
        $stmt[0]->execute() ;

        $values = $stmt[0]->fetchAll( \PDO::FETCH_ASSOC ) ;

        print_r( $values ) ;
    }
}

?>
