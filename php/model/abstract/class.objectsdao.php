<?php namespace stader\model ;

abstract class ObjectsDao extends Setup
{
    private       $keysAllowed = []   ;
    private       $functions   = null ;
    protected     $values      = []   ;
    protected     $valuesOld   = []   ;
    protected     $class       = ''   ;
    
    function __construct ( string $dbType , Array $allowedKeys , $args )
    {   // echo 'abstract class ObjectsDao extends Setup __construct' . \PHP_EOL ;

        parent::__construct( $dbType ) ;
        $this->keysAllowed = $allowedKeys ;

        switch ( self::$connect->getType() )
        {
            case "mysql"    : $this->functions = new TableDaoPdo( self::$connect , $this->class ) ; break ;
            case "pgsql"    : $this->functions = new TableDaoPdo( self::$connect , $this->class ) ; break ;
            case "sqlite"   : $this->functions = new TableDaoPdo( self::$connect , $this->class ) ; break ;
            case "xml"      : $this->functions = new TableDaoXml( self::$connect , $this->class ) ; break ;
            default: throw new \Exception() ;
            // var_dump( $this->functions ) ;
        }

        $this->setupData( $args ) ;

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
                $this->readAll( $this ) ;
                break ;
            case 'string' :
                if ( strtolower( gettype( $args[1] ) ) !== 'string' )
                    throw new \Exception( gettype( $args[0] ) . ' & ' . gettype( $args[1] ) . ' er ikke begge "string"' ) ;
                 if ( ! array_key_exists( $args[0] , $this->keysAllowed ) )
                    throw new \Exception( "'{$key}' doesn't exist in [ " . implode( ' , ' , array_keys( $this->keysAllowed ) ) . " ]" ) ;
                $this->$values[$args[0]] = $args[1] ;
                $this->readAll( $this ) ;
                break ;
            case 'array' :
                if ( count( $args[0] ) !== count( $args[1] ) )
                    throw new \Exception( 'count() for $args[0] & $args[1] er forskellige' ) ;
                foreach ( $args[0] as $key )
                {
                    if ( ! array_key_exists( $key , $this->keysAllowed ) )
                        throw new \Exception( "'{$key}' doesn't exist in [ " . implode( ' , ' , array_keys( $this->keysAllowed ) ) . " ]" ) ;
                }
                $this->values = $args[0] ;
                $this->readAll( $this ) ;
                break ;
            default :
                throw new \Exception( gettype( $args[0] ) . " : forkert input type [null,string,array]" ) ;
                break ;
        }
    }

    protected function readAll( $object ) : void
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $object ) ;

        $this->objectIDs = $this->functions->readAll( $object ) ;
        reset( $this->objectIDs ) ;
    }

    public function count()   : int { return count( $this->objectIDs ) ; }
    /*
     *  dette her virker ikke
     *  det er selvfølgelig lettere irriterende, at type hinting må slås fra
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
    
    public function getData()   { return $this->values ; }
    public function getValues() { return array_values( $this->values ) ; }
    public function getKeys()   { return array_keys( $this->values ) ; }

}

?>
