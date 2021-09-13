<?php namespace stader\model ;

abstract class ObjectsDaoTest
         extends Setup
         implements \Iterator
{
    private           $keysAllowed = []   ;
    private   static  $functions   = null ;
    protected         $values      = []   ;
    protected         $class       = ''   ;
    private           $stmt        = null ;
    private           $position    = 0    ;
    
    function __construct ( string $dbType , Array $allowedKeys )
    {   // echo 'abstract class ObjectsDao extends Setup __construct' . \PHP_EOL ;

        parent::__construct( $dbType ) ;
        $this->keysAllowed = $allowedKeys ;

        switch ( self::$connect->getType() )
        {
            case "mysql"    : self::$functions = new TableDaoPdo( self::$connect , $this->class ) ; break ;
            case "pgsql"    : self::$functions = new TableDaoPdo( self::$connect , $this->class ) ; break ;
            case "sqlite"   : self::$functions = new TableDaoPdo( self::$connect , $this->class ) ; break ;
            case "xml"      : self::$functions = new TableDaoXml( self::$connect , $this->class ) ; break ;
            default: throw new \Exception() ;
            // var_dump( self::functions ) ;
        }

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
                $this->readAllIterator( $this ) ;
                break ;
            case 'string' :
                $this->values[$args[0]] = $args[1] ;
                $this->check( $this->values ) ;
                $this->readAllIterator( $this ) ;
                break ;
            case 'array' :
                if ( count( $args[0] ) !== count( $args[1] ) )
                    throw new \Exception( 'count() for $args[0] & $args[1] er forskellige' ) ;
                foreach ( $args[0] as $i => $key )
                    $this->values[$key] = $args[$i] ;
                    unset( $i , $key ) ;
                $this->check( $this->values ) ;
                $this->readAllIterator( $this ) ;
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

// https://www.php.net/manual/en/class.iterator.php

    protected function readAllIterator( $object ) : void
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $object ) ;

        $this->stmt = self::$functions->readAllIterator( $object ) ;
    }

    public function getOne( int $index ) { return new $this->class( $index ) ; }

    private function testOne( int $index )
    {
        try {
            return $this->getOne( $index ) ;
        } catch ( \Exception ) { return false ; }
    }

    public function current()
    { 
        $row = $this->stmt->fetch( \PDO::FETCH_ASSOC , \PDO::FETCH_ORI_NEXT , $this->position ) ;
        return( $this->testOne( (int) $row['id'] ) ) ;
    }

    public function key() : int | false
    {
        $row = $this->stmt->fetch( \PDO::FETCH_ASSOC , \PDO::FETCH_ORI_NEXT , $this->position ) ;
        if ( $row === false )
             { return false ; } 
        else { return $this->getOne( (int) $row['id'] ) ; }
    }

    public function next()   : void { ++$this->position ; }

    public function rewind() : void { $this->position = 0 ; }

    public function valid()  : bool
    {
        $row = $this->stmt->fetch( \PDO::FETCH_ASSOC , \PDO::FETCH_ORI_NEXT , $this->position ) ;
        if ( $row === false )
             { return false ; } 
        else { return true ; }
    }

    public function deleteOne( $objectID )
    {
            ( new $this->class( $objectID ) )->delete() ;
    }

    public function deleteAll()
    {
        $this->position = 0 ;
        while ( $row = $this->stmt->fetch( \PDO::FETCH_ASSOC , \PDO::FETCH_ORI_NEXT , $this->position ) )
        {
            $this->deleteOne( (int) $row['id'] ) ;
        }   unset( $row ) ;
    }
    
/*
    public function count()  : int {}

    public function getAll() : array 
    {
        $allObjects = [] ;
        foreach ( $this->objectIDs as $oneID )
        {
            $allObjects[] = new $this->class( (int) $oneID ) ;
        }
    return  $allObjects ; }


 */

    public function getData()   { return $this->values ; }
    public function getValues() { return array_values( $this->values ) ; }
    public function getKeys()   { return array_keys( $this->values ) ; }
}

?>
