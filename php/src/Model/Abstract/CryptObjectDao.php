<?php namespace Stader\Model\Abstract ;

/*

create table if not exists usercrypt
(
    id      int primary key ,
    salt    varchar(255) ,
    algo    varchar(255) ,
    tag     varchar(255) ,
    data    text
) ;

 */

use \Stader\Model\Connect\DataSetup ;
use \Stader\Model\DatabaseAccessObjects\{TableCryptDaoPdo} ;
use \Stader\Model\Traits\ObjectDaoFunctions ;

abstract class CryptObjectDao extends DataSetup
{
    protected         $keysAllowed = []   ;
    private   static  $functions   = null ;
    protected         $values      = []   ;
    protected         $valuesOld   = []   ;
    protected         $class       = ''   ;
    private           $classCrypt  = ''   ;
    
    function __construct ()
    {   // echo 'abstract class ObjectDao extends Setup __construct' . \PHP_EOL ;

        parent::__construct() ;

        switch ( self::$connect->getType() )
        {
            case "mysql"        : self::$functions = new TableCryptDaoPdo( self::$connect , $this->class ) ; break ;
            case "pgsql"        : self::$functions = new TableCryptDaoPdo( self::$connect , $this->class ) ; break ;
            case "sqlite"       : self::$functions = new TableCryptDaoPdo( self::$connect , $this->class ) ; break ;
            case "xml"          : self::$functions = new TableCryptDaoXml( self::$connect , $this->class ) ; break ;
            default: throw new \Exception() ;
            // var_dump( self::$functions ) ;
        }

    }

    protected function setupData ( $args )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $args ) ;
        /*
         *  gettype( $args[0] ) === 'integer' 
         *      hent et Object på basis af et id
         *      $testObject = new Object( id ) ;
         *  gettype( $args[0] ) === 'array'
         *      opret en Object på basis af værdierne i $args[0]
         *      $testObject = new Object( $args[0] )
         *  gettype( $args[0] ) === ['string','array'] , gettype( $args[1] ) === ['string','array']
         *      hent et Object på basis af værdierne i $args[0],$args[1]
         *      $testObject = new Object( $keys , $values )
         */
        switch ( count( $args ) )
        {
            case 1 :
                switch ( strtolower( gettype( $args[0] ) ) )
                {
                    case 'integer' :
                        $this->values['id'] = $args[0] ;
                        $this->values       = $this->read( $this ) ;
                       break ;
                    case 'array' :
                        switch ( count( $args[0] ) )
                        {
                            /*
                             *  count( $args[0] ) === count( $this->allowedKeys ) : nyt Object, der skal oprettes
                             */
                            case count( $this->keysAllowed ) :
                                $this->check( $args[0] ) ;
                                $this->values       = ( new \ArrayObject( $args[0] ) )->getArrayCopy() ;
                                $this->values['id'] = $this->create( $this ) ;
                                $this->notify( 'create' ) ;
                                break ;
                            default :
                                throw new \Exception( count( $args[0] ) . " : forkert antal parametre [" . count( $this->keysAllowed ) . "]" ) ;
                                break ;
                        }
                        break ;
                    default :
                        throw new \Exception( gettype( $args[0] ) . " : forkert input type [integer,array]" ) ;
                        break ;
                }
                break ;
            case 2 :
                switch ( strtolower( gettype( $args[0] ) ) )
                {
                    case 'string' :
                        $args[0] = [ $args[0] ] ;
                        $args[1] = [ $args[1] ] ;
                    case 'array' :
                        if ( count( $args[0] ) !== count( $args[1] ) )
                            throw new \Exception( 'count() for $args[0] & $args[1] er forskellige' ) ;
                        $args[0] = array_combine( $args[0] , $args[1] ) ;
                        $this->check( $args[0] ) ;
                        $this->values = ( new \ArrayObject( $args[0] ) )->getArrayCopy() ;
                        break ;
                    default :
                        throw new \Exception( gettype( $args[0] ) . " : forkert input type [string,array]" ) ;
                        break ;
                }
                $this->values = $this->read( $this ) ;
                break ;
            default :
                throw new \Exception( count( $args ) . " : forkert antal parametre [1,2]" ) ;
                break ;
        }
        $this->valuesOld = ( new \ArrayObject( $this->values ) )->getArrayCopy() ;

    }

    use ObjectDaoFunctions ;

}

?>
