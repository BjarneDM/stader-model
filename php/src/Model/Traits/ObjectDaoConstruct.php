<?php namespace Stader\Model\Traits ;

use \Stader\Model\DatabaseAccessObjects\{TableDaoPdo,TableCryptDaoPdo} ;

trait ObjectDaoConstruct
{
    protected         $database  = '' ;
    private   static  $functions = null ;

    function __construct ()
    {   // echo 'abstract class ObjectDao extends Setup __construct' . \PHP_EOL ;

        $this->getSettings() ;

        switch ( self::$iniSettings[$this->database]['method'] )
        {
            case "encrypted"    : self::$functions = new TableCryptDaoPdo( $this->database , $this->class ) ; break ;
            case "mysql"        : self::$functions = new TableDaoPdo( $this->database , $this->class ) ; break ;
            case "pgsql"        : self::$functions = new TableDaoPdo( $this->database , $this->class ) ; break ;
            case "sqlite"       : self::$functions = new TableDaoPdo( $this->database , $this->class ) ; break ;
            case "xml"          : self::$functions = new TableDaoXml( $this->database , $this->class ) ; break ;
            default: throw new \Exception() ;
            // var_dump( self::$functions ) ;
        }

    }

}

?>
