<?php namespace Stader\Model\Abstract ;

use \Stader\Model\Connect\LogSetup ;
use \Stader\Model\Abstract\{TableDaoPdo} ;

abstract class LogObjectDao extends LogSetup
{
    protected         $keysAllowed = []   ;
    private   static  $functions   = null ;
    protected         $values      = []   ;
    protected         $valuesOld   = []   ;
    protected         $class       = ''   ;
    
    function __construct ()
    {   // echo 'abstract class LogObjectDao extends LogSetup __construct' . \PHP_EOL ;

        parent::__construct( 'logs' ) ;

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

    protected function setupData ( $args )
    {
        /*
         *  gettype( $args[0] ) === 'array'
         *      opret en Log på basis af værdierne i $args[0]
         *      $testLog = new Log( $args[0] )
         *
         *  idéen er, at den almindelige drift !!!KUN!!! kan oprette & læse logs
         */
        switch ( count( $args ) )
        {
            case 1 :
                switch ( strtolower( gettype( $args[0] ) ) )
                {
                    case 'array' :
                        /*
                         *  count( $args[0] ) === count( $this->keysAllowed ) : ny Log, der skal oprettes
                         */
                        switch ( count( $args[0] ) )
                        {
                            case count( $this->keysAllowed ) :
                                $this->check( $args[0] ) ;
                                $this->values       = ( new \ArrayObject( $args[0] ) )->getArrayCopy() ;
                                $this->values['id'] = $this->create( $this ) ;
                                $this->valuesOld    = ( new \ArrayObject( $this->values ) )->getArrayCopy() ;
                                $this->notify( 'create' ) ;
                                break ;
                            default :
                                throw new \Exception( count( $args[0] ) . " : forkert antal parametre [" . count( $this->keysAllowed ) . "]" ) ;
                                break ;
                        }
                        break ;
                    default :
                        throw new \Exception( gettype( $args[0] ) . " : forkert input type [array]" ) ;
                        break ;
                }
                break ;
            default :
                throw new \Exception( count( $args ) . " : forkert antal parametre [1]" ) ;
                break ;
        }
    }

    protected function create( $object ) : int
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $array ) ;

    return self::$functions->create( $object ) ; }

    protected function read( $object ) : Array
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $args ) ;

        $this->notify( 'read' ) ;
    return self::$functions->readOne( $object ) ; }

    public function getData()   { return $this->values ; }
    public function getValues() { return array_values( $this->values ) ; }
    public function getKeys()   { return array_keys( $this->values ) ; }

    /*
     *  default minimalt integritets check
     */
    protected function check( Array &$toCheck )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $toCheck ) ;

        foreach ( array_keys( $toCheck ) as $key )
        {
            if ( ! array_key_exists( $key , $this->keysAllowed ) )
                throw new \Exception( "'{$key}' doesn't exist in [" . implode( ',' , array_keys( $this->keysAllowed ) ) . "]" ) ;

            switch ( $this->keysAllowed[$key] )
            {
                case 'int' :
                case 'integer' :
                    break ;
                case 'bool' :
                    break ;
            }

        }
    }

    /*
     *  denne skal udfyldes i de aktuelle class
     *  der har brug for dette
     */
    protected function notify ( string $action ) : void {}

}

?>
