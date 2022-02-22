<?php namespace Stader\Model\Traits ;

use \Stader\Model\DatabaseAccessObjects\{TableDaoPdo,TableCryptDaoPdo} ;

trait ObjectDaoConstruct
{   
    private static $functions = null ;

    function __construct ( $dbType , $class , $allowedKeys  )
    {   echo "trait ObjectDaoConstruct"  . \PHP_EOL ;
        echo 'abstract class DataObjectDao extends Setup __construct' . \PHP_EOL ;
        // print_r( [ 'dbType' => $dbType , 'class' => $class , 'allowedKeys' => $allowedKeys ] ) ;

        $this->getSettings() ;

        if ( ! self::$functions )
        {   echo "switch ( ". self::$iniSettings[$dbType]['method'] ." )" . \PHP_EOL ;
            print_r( [ 'dbType' => $dbType , 'class' => $class , 'allowedKeys' => $allowedKeys ] ) ;
            switch ( self::$iniSettings[$dbType]['method'] )
            {   
                case "cryptdata"    : self::$functions = new TableCryptDaoPdo( $dbType , $class , $allowedKeys ) ; break ;
                case "mysql"        : self::$functions = new TableDaoPdo     ( $dbType , $class , $allowedKeys ) ; break ;
                case "pgsql"        : self::$functions = new TableDaoPdo     ( $dbType , $class , $allowedKeys ) ; break ;
                case "sqlite"       : self::$functions = new TableDaoPdo     ( $dbType , $class , $allowedKeys ) ; break ;
                case "xml"          : self::$functions = new TableDaoXml     ( $dbType , $class , $allowedKeys ) ; break ;
                default: throw new \Exception() ;
                // var_dump( $this->functions ) ;
            }
        }
    }

}

?>
