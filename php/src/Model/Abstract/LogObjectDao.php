<?php namespace Stader\Model\Abstract ;

use \Stader\Model\Connect\LogSetup ;
use \Stader\Model\DatabaseAccessObjects\{TableDaoPdo} ;
use \Stader\Model\Traits\{ObjectDaoConstruct,ObjectDaoFunctions} ;

abstract class LogObjectDao extends LogSetup
{
    protected         $keysAllowed = []   ;
    private   static  $functions   = null ;
    protected         $values      = []   ;
    protected         $valuesOld   = []   ;
    protected         $class       = ''   ;

    use ObjectDaoConstruct ;

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

    use ObjectDaoFunctions ;

}

?>
