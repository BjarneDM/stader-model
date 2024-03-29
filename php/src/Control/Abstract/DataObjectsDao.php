<?php namespace Stader\Control\Abstract ;

abstract class DataObjectsDao
         implements \Iterator
{
    protected $position = 0 ;
    protected $values = [] ;
    
    function __construct ()
    {   // echo 'abstract class ObjectsDao extends Setup __construct' . \PHP_EOL ;

    }

    protected function setupData ( $thisClass , $args )
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
                $this->check( $thisClass , $this->values ) ;
                break ;
            case 'array' :
                if ( count( $args[0] ) !== count( $args[1] ) )
                    throw new \Exception( 'count() for $args[0] & $args[1] er forskellige' ) ;
                $this->values  = array_combine( $args[0] , $args[1] ) ;
                $this->check( $thisClass , $this->values ) ;
                break ;
            default :
                throw new \Exception( gettype( $args[0] ) . " : forkert input type [null,string,array]" ) ;
                break ;
        }
    }

    /*
     *  default minimalt integritets check
     */
    protected function check( $thisClass , Array &$toCheck ): void
    {   echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        print_r([ $thisClass, $toCheck] ) ;
//return ;
        foreach ( array_keys( $toCheck ) as $key )
        {
            if ( ! array_key_exists( $key , $thisClass::$allowedKeys ) )
                throw new \Exception( "'{$key}' doesn't exist in [" . implode( ',' , array_keys( $thisClass::$allowedKeys ) ) . "]" ) ;
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

    public function getData()   { return $this->values ; }
    public function getValues() { return array_values( $this->values ) ; }
    public function getKeys()   { return array_keys( $this->values ) ; }

// https://www.php.net/manual/en/class.iterator.php

    protected function getOne( $baseClass , int $index ) 
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // echo $this->class. \PHP_EOL ;
        // echo "int \$index : {$index}" . \PHP_EOL ;
        return new $baseClass( $index ) ; 
    }

    abstract public function rewind() : void ;
        // $this->functions->rewind( $this ) ;
        // $this->position = 0 ;

    abstract public function count() : int ;
        // return $this->functions->count() ;

    abstract public function next() : void ;
        // $this->row = $this->functions->next() ;
        // ++$this->position ; 

    abstract public function valid() : bool ;
        // return $this->functions->valid() ;

    abstract public function current() : object ;
        // return $this->getOne( $this->functions->current() ) ;

    abstract public function key() : mixed ;
        // return $this->functions->key() ;
    
/*
    public function getAll() : array 
    {
        $allObjects = [] ;
        foreach ( $this->objectIDs as $oneID )
        {
            $allObjects[] = new $this->class( (int) $oneID ) ;
        }
    return  $allObjects ; }
 */

}

?>
