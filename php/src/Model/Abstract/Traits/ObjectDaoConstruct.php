<?php namespace Stader\Model\Abstract\Traits ;

use \Stader\Model\DatabaseAccessObjects\{TableDaoPdo,TableCryptDaoPdo} ;
use \Stader\Model\Settings ;

/*
 *  Idéen er, at der kan være flere DAO & at DAO bestemmes pr Object / DB tabel
 *  f.eks kan der logges ned i en DB medens resten af tabellerne er i en primær DB
 */


trait ObjectDaoConstruct
{
    // holder styr på hvilke $dbTypes => $method denne contructor er blevet kaldt med
    private   static $dbTypes   = [] ;
    // indeholder DB funktionerne for den enkelte $method
    private   static $functions = [] ;
    protected        $theDBtype = '' ;
    protected static Settings $iniSettings ;

    function __construct ( string $dbType , string $thisClass , array $allowedKeys  )
    {   // echo "trait ObjectDaoConstruct"  . \PHP_EOL ;
        // echo 'abstract class DataObjectDao extends Setup __construct' . \PHP_EOL ;
        // print_r( [ 'dbType' => $dbType , 'thisClass' => $thisClass , 'allowedKeys' => $allowedKeys ] ) ;

        $this->theDBtype = $dbType ;
        if ( ! isset( self::$iniSettings ) ) self::$iniSettings = Settings::getInstance() ;

        if ( ! isset( self::$dbTypes[ $dbType ] ) )
        {   // echo "switch ( ". self::$iniSettings->getSetting($dbType, 'method') ." )" . \PHP_EOL ;
            // print_r( [ 'dbType' => $dbType , 'thisClass' => $thisClass ] ) ;

            $method = self::$iniSettings->getSetting($dbType, 'method') ;
            self::$dbTypes[ $dbType ] = $method ;
            /*
             *  dette trick kan IKKE benyttes, da der i de enkelte DAO skabes et PDOconnect 
             *  på basis af self::$iniSettings->getSetting($dbType, 'dbname')
             *  & der kan være flere PDOconnect pr $method
             */
            // if ( ! isset( self::$functions[ $method ] ) )
            // {
                switch ( $method )
                {   
                    case "cryptdata"    : self::$functions[ $method ] = new TableCryptDaoPdo( $dbType ) ; break ;
                    case "mysql"        : self::$functions[ $method ] = new TableDaoPdo     ( $dbType ) ; break ;
                    case "pgsql"        : self::$functions[ $method ] = new TableDaoPdo     ( $dbType ) ; break ;
                    case "sqlite"       : self::$functions[ $method ] = new TableDaoPdo     ( $dbType ) ; break ;
                    case "xml"          : self::$functions[ $method ] = new TableDaoXml     ( $dbType ) ; break ;
                    default: throw new \Exception() ;
                    // var_dump( $this->functions ) ;
                }
            // }
        }
    }

}

?>
