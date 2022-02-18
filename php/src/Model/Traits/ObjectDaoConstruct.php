<?php namespace Stader\Model\Traits ;

use \Stader\Model\DatabaseAccessObjects\{TableDaoPdo} ;

trait ObjectDaoConstruct
{

    function __construct ()
    {   // echo 'abstract class ObjectDao extends Setup __construct' . \PHP_EOL ;

        $this->getSettings() ;

        switch ( 'mysql' )
        {
            case "mysql"        : self::$functions = new TableDaoPdo( 'data' , $this->class ) ; break ;
            case "pgsql"        : self::$functions = new TableDaoPdo( 'data' , $this->class ) ; break ;
            case "sqlite"       : self::$functions = new TableDaoPdo( 'data' , $this->class ) ; break ;
            case "xml"          : self::$functions = new TableDaoXml( 'data' , $this->class ) ; break ;
            default: throw new \Exception() ;
            // var_dump( self::$functions ) ;
        }

    }

}

?>
