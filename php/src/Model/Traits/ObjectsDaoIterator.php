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

    private function getOne( $object , int $index )
    {   // print_r( $object ) ;
        return new $object::$thisClass( $index ) ; 
    }

    public function rewind() : void 
    {
        $method = self::$iniSettings[ $this->theDBtype ]['method'] ;
        self::$functions[ $method ]->rewind( $this ) ;
        $this->position = 0 ;
    }

    public function count() : int
    {
        $method = self::$iniSettings[ $this->theDBtype ]['method'] ;
        return self::$functions[ $method ]->count( $this ) ;
    }

    public function next() : void 
    {
        $method = self::$iniSettings[ $this->theDBtype ]['method'] ;
        $this->row = self::$functions[ $method ]->next( $this ) ;
        ++$this->position ; 
    }

    public function valid() : bool
    {
        $method = self::$iniSettings[ $this->theDBtype ]['method'] ;
        return self::$functions[ $method ]->valid( $this ) ;
    }

    public function current() : object
    {   // echo $this->class . \PHP_EOL ;
        $method = self::$iniSettings[ $this->theDBtype ]['method'] ;
        $id = self::$functions[ $method ]->current( $this ) ;
        return $this->getOne( $this , $id ) ;
    }

    public function key() : int | false
    {
        $method = self::$iniSettings[ $this->theDBtype ]['method'] ;
        return self::$functions[ $method ]->key( $this ) ;
    }

    public function deleteAll() : void
    {
        $method = self::$iniSettings[ $this->theDBtype ]['method'] ;
        self::$functions[ $method ]->deleteAll( $this ) ;
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
