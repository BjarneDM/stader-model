<?php namespace Stader\Model\Traits ;

use \Stader\Model\DatabaseAccessObjects\{TableDaoPdo,TableCryptDaoPdo} ;

trait ObjectDaoConstruct
{
    protected $database  = '' ;
    private   $functions = null ;

    function __construct ()
    {   // echo 'abstract class ObjectDao extends Setup __construct' . \PHP_EOL ;

        $this->getSettings() ;

        switch ( self::$iniSettings[$this->database]['method'] )
        {
            case "cryptdata"    : $this->functions = new TableCryptDaoPdo( $this->database , $this->class ) ; break ;
            case "mysql"        : $this->functions = new TableDaoPdo( $this->database , $this->class ) ; break ;
            case "pgsql"        : $this->functions = new TableDaoPdo( $this->database , $this->class ) ; break ;
            case "sqlite"       : $this->functions = new TableDaoPdo( $this->database , $this->class ) ; break ;
            case "xml"          : $this->functions = new TableDaoXml( $this->database , $this->class ) ; break ;
            default: throw new \Exception() ;
            // var_dump( $this->functions ) ;
        }

    }

}

?>
