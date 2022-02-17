<?php namespace Stader\Model\Abstract ;

use \Stader\Model\Connect\DataSetup ;
use \Stader\Model\DatabaseAccessObjects\TableDaoPdo ;
use \Stader\Model\Traits\{ObjectDaoConstruct,ObjectsDaoIterator} ;

abstract class DataObjectsDao
         extends DataSetup
         implements \Iterator
{
    protected         $keysAllowed = []   ;
    private   static  $functions   = null ;
    protected         $class       = ''   ;
    protected         $values      = []   ;
    private           $position    = 0    ;
    
    use ObjectDaoConstruct ;

    protected function setupData ( $args )
    {
        /*
         *  gettype( $args[0] ) === 'null' 
         *      hent alle Objects
         *      $testObjects = new Objects() ;
         *  gettype( $args[0] ) === 'array' | 'string'
         *      hent alle Objects på basis af værdierne i $args[0] , $args[1] 
         *      $testObjects = new Objets( $args[0] , $args[1] )
         */
        if ( ! isset( $args[0] ) ) { $args = [] ; $args[0] = null ; }
        switch ( strtolower( gettype( $args[0] ) ) )
        {
            case 'null' :
                break ;
            case 'string' :
                $this->values[$args[0]] = $args[1] ;
                $this->check( $this->values ) ;
                break ;
            case 'array' :
                if ( count( $args[0] ) !== count( $args[1] ) )
                    throw new \Exception( 'count() for $args[0] & $args[1] er forskellige' ) ;
                $this->values = array_combine( $args[0] , $args[1] ) ;
                $this->check( $this->values ) ;
                break ;
            default :
                throw new \Exception( gettype( $args[0] ) . " : forkert input type [null,string,array]" ) ;
                break ;
        }
    }

    use ObjectsDaoIterator ;

}

?>
