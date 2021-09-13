<?php namespace stader\model ;

abstract class ObjectsDaoOld extends Setup
{
    private           $keysAllowed = []   ;
    private   static  $functions   = null ;
    protected         $values      = []   ;
    protected         $valuesOld   = []   ;
    protected         $class       = ''   ;
    protected         $objectIDs   = []   ;
    
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
         *      hent alle Objects pŒ basis af v¾rdierne i $args[0] , $args[1] 
         *      $testObjects = new Objets( $args[0] , $args[1] )
         */
        if ( ! isset( $args[0] ) ) { $args = [] ; $args[0] = null ; }
        switch ( strtolower( gettype( $args[0] ) ) )
        {
            case 'null' :
                $this->readAll( $this ) ;
                break ;
            case 'string' :
                $this->values[$args[0]] = $args[1] ;
                $this->check( $this->values ) ;
                $this->readAll( $this ) ;
                break ;
            case 'array' :
                if ( count( $args[0] ) !== count( $args[1] ) )
                    throw new \Exception( 'count() for $args[0] & $args[1] er forskellige' ) ;
                foreach ( $args[0] as $i => $key )
                    $this->values[$key] = $args[$i] ;
                    unset( $i , $key ) ;
                $this->check( $this->values ) ;
                $this->readAll( $this ) ;
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

    protected function readAll( $object ) : void
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $object ) ;

        $this->objectIDs = self::$functions->readAll( $object ) ;
        reset( $this->objectIDs ) ;
    }

    public function count()   : int { return count( $this->objectIDs ) ; }
    /*
     *  dette her virker ikke
     *  det er selvf¿lgelig lettere irriterende, at type hinting mŒ slŒs fra
    public function reset()   : $this->class | false { return $this->testOne( (int)   reset( $this->objectIDs ) ) ; }
     */
    public function reset()   { return $this->testOne( (int)   reset( $this->objectIDs ) ) ; }
    public function prev()    { return $this->testOne( (int)    prev( $this->objectIDs ) ) ; }
    public function current() { return $this->testOne( (int) current( $this->objectIDs ) ) ; }
    public function next()    { return $this->testOne( (int)    next( $this->objectIDs ) ) ; }
    public function end()     { return $this->testOne( (int)     end( $this->objectIDs ) ) ; }

    private function testOne( int $index )
    {
        try {
            return $this->getOne( $index ) ;
        } catch ( \Exception ) { return false ; }
    }

    public function getOne( int $index ) { return new $this->class( $index ) ; }

    public function getAll() : array 
    {
        $allObjects = [] ;
        foreach ( $this->objectIDs as $oneID )
        {
            $allObjects[] = new $this->class( (int) $oneID ) ;
        }
    return  $allObjects ; }

    public function deleteOne( $objectID )
    {
            ( new $this->class( $objectID ) )->delete() ;
            unset( $this->objectIDs[ array_search( $objectID , $this->objectIDs ) ] ) ;
    }

    public function deleteAll()
    {
        foreach ( $this->objectIDs as $key => $objectID )
        {
            $this->deleteOne( $objectID ) ;
        }   unset( $key , $objectID ) ;
    }
    
    public function getData()   { return $this->values ; }
    public function getValues() { return array_values( $this->values ) ; }
    public function getKeys()   { return array_keys( $this->values ) ; }

}

?>
