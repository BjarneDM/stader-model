<?php namespace Stader\Control\Abstract ;

abstract class DataObjectsDao
         implements \Iterator
{
    private     $keysAllowed = []   ;
    protected   $class       = ''   ;
    protected   $position    = 0    ;
    
    function __construct ()
    {   // echo 'abstract class ObjectsDao extends Setup __construct' . \PHP_EOL ;

    }

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
                $this->values  = array_combine( $args[0] , $args[1] ) ;
                $this->check( $this->values ) ;
                break ;
            default :
                throw new \Exception( gettype( $args[0] ) . " : forkert input type [null,string,array]" ) ;
                break ;
        }
    }

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
        }
    }

    public function getData()   { return [] ; }
    public function getValues() { return [] ; }
    public function getKeys()   { return [] ; }

// https://www.php.net/manual/en/class.iterator.php

    protected function getOne( int $index ) 
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // echo $this->class. \PHP_EOL ;
        // echo "int \$index : {$index}" . \PHP_EOL ;
        return new $this->class( $index ) ; 
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

    abstract public function key() : int | false ;
        // return $this->functions->key() ;

    abstract public function deleteAll() : void ;
        // $this->functions->deleteAll( $this ) ;
    
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
