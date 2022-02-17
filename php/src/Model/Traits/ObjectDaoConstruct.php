<?php namespace Stader\Model\Traits ;

use \Stader\Model\DatabaseAccessObjects\{TableDaoPdo} ;

trait ObjectDaoConstruct
{

    function __construct ()
    {   // echo 'abstract class ObjectDao extends Setup __construct' . \PHP_EOL ;

        parent::__construct() ;

        switch ( self::$connect->getType() )
        {
            case "mysql"        : self::$functions = new TableDaoPdo( self::$connect , $this->class ) ; break ;
            case "pgsql"        : self::$functions = new TableDaoPdo( self::$connect , $this->class ) ; break ;
            case "sqlite"       : self::$functions = new TableDaoPdo( self::$connect , $this->class ) ; break ;
            case "xml"          : self::$functions = new TableDaoXml( self::$connect , $this->class ) ; break ;
            default: throw new \Exception() ;
            // var_dump( self::$functions ) ;
        }

    }

}

?>
