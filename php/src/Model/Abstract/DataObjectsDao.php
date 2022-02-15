<?php namespace Stader\Model\Abstract ;

use \Stader\Model\Connect\DataSetup ;

abstract class DataObjectsDao
         extends DataSetup
         implements \Iterator
{
    private           $keysAllowed = []   ;
    private   static  $functions   = null ;
    protected         $class       = ''   ;
    private           $position    = 0    ;
    
    function __construct ( string $dbType , Array $allowedKeys )
    {   // echo 'abstract class ObjectsDao extends Setup __construct' . \PHP_EOL ;

        parent::__construct( $dbType ) ;
        $this->keysAllowed = $allowedKeys ;

        switch ( self::$connect->getType() )
        {
            case "mysql"        : self::$functions = new TableDaoPdo( self::$connect , $this->class ) ; break ;
            case "pgsql"        : self::$functions = new TableDaoPdo( self::$connect , $this->class ) ; break ;
            case "sqlite"       : self::$functions = new TableDaoPdo( self::$connect , $this->class ) ; break ;
            case "xml"          : self::$functions = new TableDaoXml( self::$connect , $this->class ) ; break ;
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

    private function getOne( int $index ) { return new $this->class( $index ) ; }

    public function rewind() : void 
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // echo $this->class . \PHP_EOL ;
        self::$functions->rewind( $this ) ;
        $this->position = 0 ;
    }

    public function count() : int
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // echo $this->class . \PHP_EOL ;
        return self::$functions->count( $this ) ;
    }

    public function next() : void 
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // echo $this->class . \PHP_EOL ;
        $this->row = self::$functions->next( $this ) ;
        ++$this->position ; 
    }

    public function valid() : bool
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // echo $this->class . \PHP_EOL ;
        return self::$functions->valid( $this ) ;
    }

    public function current() : object
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // echo $this->class . \PHP_EOL ;
        return $this->getOne( self::$functions->current( $this ) ) ;
    }

    public function key() : int | false
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // echo $this->class . \PHP_EOL ;
        return self::$functions->key( $this ) ;
    }

    public function deleteAllOld() : void
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // echo $this->class . \PHP_EOL ;
        self::$functions->deleteAll( $this ) ;
    }
    
    public function deleteAll() : void
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // echo $this->class . \PHP_EOL ;
        self::$functions->deleteAll() ;
    }
    
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
