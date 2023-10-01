<?php namespace Stader\Model\Abstract ;

use \Stader\Model\Abstract\Traits\{ObjectDaoConstruct,ObjectsDaoIterator} ;
use \Stader\Model\Settings ;

abstract class DataObjectsDao
         implements \Iterator
{
    protected $values = [] ;
    private ?string $orderBy = null ;
    private   $position  =  0 ;
    protected static Settings $iniSettings ;
    private $row ;

    use ObjectDaoConstruct ;

    protected function setupData ( $args ) : void
    {
        self::$iniSettings = Settings::getInstance() ;

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
                $this->check( $this , $this->values ) ;
                break ;
            case 'array' :
                if ( count( $args[0] ) !== count( $args[1] ) )
                    throw new \Exception( 'count() for $args[0] & $args[1] er forskellige' ) ;
                $this->values = array_combine( $args[0] , $args[1] ) ;
                $this->check( $this , $this->values ) ;
                break ;
            default :
                throw new \Exception( gettype( $args[0] ) . " : forkert input type [null,string,array]" ) ;
                break ;
        }
    }

    public function setOrderBy ( array $columns ) : void
    {
        $this->check( $this , $columns ) ;

        $orders = [ 'ASC', 'DESC', '', null ] ;
        $orderBy = [] ;
        foreach ( $columns as $column => $order )
        {
            $order = strtoupper( $order ) ;
            if( ! in_array( $order , $orders ) )
                throw new \Exception( $order . " : forkert order type [".implode(',',$orders)."]" ) ;
            $orderBy[] = "{$column} {$order}" ;
        }
        $this->orderBy = implode( ', ', $orderBy ) ; 
    }

    public function getOrderBy () : ?string
    {
        return $this->orderBy ;
    }

    use ObjectsDaoIterator ;

}

?>
