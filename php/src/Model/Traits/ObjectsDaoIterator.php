<?php namespace Stader\Model\Traits ;

trait ObjectsDaoIterator
{
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

    public function getData()   { return $this->values ; }
    public function getValues() { return array_values( $this->values ) ; }
    public function getKeys()   { return array_keys( $this->values ) ; }

// https://www.php.net/manual/en/class.iterator.php

    private function getOne( int $index ) { return new $this->class( $index ) ; }

    public function rewind() : void 
    {
        self::$functions->rewind( $this ) ;
        $this->position = 0 ;
    }

    public function count() : int
    {
        return self::$functions->count( $this ) ;
    }

    public function next() : void 
    {
        $this->row = self::$functions->next( $this ) ;
        ++$this->position ; 
    }

    public function valid() : bool
    {
        return self::$functions->valid( $this ) ;
    }

    public function current() : object
    {   // echo $this->class . \PHP_EOL ;
        $id = self::$functions->current( $this ) ;
        return $this->getOne( $id ) ;
    }

    public function key() : int | false
    {
        return self::$functions->key( $this ) ;
    }

    public function deleteAll() : void
    {
        self::$functions->deleteAll( $this ) ;
    }
    
    public function getIDs() : array 
    {
        $IDs = [] ;
        $this->rewind() ;
        while ( $this->valid() )
        {
            $IDs[] = $this->key() ;
            $this->next() ;
        }
    return $IDs ; }


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
