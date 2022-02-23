<?php namespace Stader\Model\Traits ;

use \Stader\Model\DatabaseAccessObjects\{TableDaoPdo,TableCryptDaoPdo} ;

trait ObjectDaoConstruct
{
    private   static $dbTypes   = [] ;
    private   static $functions = [] ;
    protected        $theDBtype = '' ;

    function __construct ( string $dbType , string $thisClass , array $allowedKeys  )
    {   // echo "trait ObjectDaoConstruct"  . \PHP_EOL ;
        // echo 'abstract class DataObjectDao extends Setup __construct' . \PHP_EOL ;
        // print_r( [ 'dbType' => $dbType , 'thisClass' => $thisClass , 'allowedKeys' => $allowedKeys ] ) ;

        $this->getSettings() ;
        $this->theDBtype = $dbType ;

        if ( ! isset( self::$dbTypes[ $dbType ] ) )
        {   // echo "switch ( ". self::$iniSettings[$dbType]['method'] ." )" . \PHP_EOL ;
            // print_r( [ 'dbType' => $dbType , 'thisClass' => $thisClass ] ) ;

            $method = self::$iniSettings[$dbType]['method'] ;
            self::$dbTypes[ $dbType ] = $method ;
            switch ( $method )
            {   
                case "cryptdata"    : self::$functions[ $method ] = new TableCryptDaoPdo( $dbType , $thisClass , $allowedKeys ) ; break ;
                case "mysql"        : self::$functions[ $method ] = new TableDaoPdo     ( $dbType , $thisClass , $allowedKeys ) ; break ;
                case "pgsql"        : self::$functions[ $method ] = new TableDaoPdo     ( $dbType , $thisClass , $allowedKeys ) ; break ;
                case "sqlite"       : self::$functions[ $method ] = new TableDaoPdo     ( $dbType , $thisClass , $allowedKeys ) ; break ;
                case "xml"          : self::$functions[ $method ] = new TableDaoXml     ( $dbType , $thisClass , $allowedKeys ) ; break ;
                default: throw new \Exception() ;
                // var_dump( $this->functions ) ;
            }
        }
    }

}

?>
