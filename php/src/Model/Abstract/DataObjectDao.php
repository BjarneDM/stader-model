<?php namespace Stader\Model\Abstract ;

use \Stader\Model\DatabaseAccessObjects\{TableDaoPdo} ;
use \Stader\Model\Traits\{ObjectDaoConstruct,ObjectDaoFunctions,MagicMethods} ;

abstract class DataObjectDao
{
    protected $values      = []   ;
    protected $valuesOld   = []   ;
    
    use ObjectDaoConstruct ;

    protected function setupObject ( $thisClass , $args )
    {
        switch ( $thisClass::$dbType )
        {
            case 'data' :
            case 'cryptdata' :
                $this->setupData( $thisClass , $args ) ;
                break ;
            case 'logs' :
            case 'cryptlogs' :
                $this->setupLogs( $thisClass , $args ) ;
                break ;
        }
    }

    protected function setupData ( $thisClass , $args )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $args ) ;
        /*
         *  setupData tager sig af C og R delene af CRUD
         *
         *  Create :
         *  --------
         *  gettype( $args[0] ) === 'array'
         *      opret en Object på basis af værdierne i $args[0]
         *      $testObject = new Object( $args[0] )
         *
         *  Read :
         *  ------
         *  gettype( $args[0] ) === 'integer' 
         *      hent et Object på basis af et id
         *      $testObject = new Object( id ) ;
         *  gettype( $args[0] ) === ['string','array'] , gettype( $args[1] ) === ['string','array']
         *      hent et Object på basis af værdierne i $args[0],$args[1]
         *      $testObject = new Object( $keys , $values )
         */
        switch ( count( $args ) )
        {
            case 1 :
                switch ( strtolower( gettype( $args[0] ) ) )
                {
                    case 'null' :
                        $this->values = $this->readNull( $this ) ;
                        break ;
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
                            case count( $thisClass::$allowedKeys ) :
                                $this->check( $thisClass , $args[0] ) ;
                                $this->values       = ( new \ArrayObject( $args[0] ) )->getArrayCopy() ;
                                $this->values['id'] = $this->create( $this ) ;
                                $this->notify( 'create' ) ;
                                break ;
                            default :
                                throw new \Exception( count( $args[0] ) . " : forkert antal parametre [" . count( $thisClass::$allowedKeys ) . "]" ) ;
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
                        $this->check( $thisClass , $args[0] ) ;
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

    protected function setupLogs ( $thisClass , $args )
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
                         *  count( $args[0] ) === count( $class::allowedKeys ) : ny Log, der skal oprettes
                         */
                        switch ( count( $args[0] ) )
                        {
                            case count( $thisClass::$allowedKeys ) :
                                $this->check( $thisClass , $args[0] ) ;
                                $this->values       = ( new \ArrayObject( $args[0] ) )->getArrayCopy() ;
                                $this->values['id'] = $this->create( $this ) ;
                                $this->valuesOld    = ( new \ArrayObject( $this->values ) )->getArrayCopy() ;
                                $this->notify( 'create' ) ;
                                break ;
                            default :
                                throw new \Exception( count( $args[0] ) . " : forkert antal parametre [" . count( $thisClass::$allowedKeys ) . "]" ) ;
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

    use ObjectDaoFunctions ;
    use MagicMethods ;

}

?>
