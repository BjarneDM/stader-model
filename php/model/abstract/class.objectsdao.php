<?php namespace stader\model ;

abstract class ObjectsDao extends Setup
{
    private   $functions = null ;
    protected $objectIDs = [] ;
    protected $class     = '' ;
    
    function __construct ( $dbType )
    {   // echo 'abstract class ObjectsDao extends Setup __construct' . \PHP_EOL ;

        parent::__construct( $dbType ) ;

        switch ( self::$connect->getType() )
        {
            case "mysql"    : $this->functions = new TableDaoPdo( self::$connect ) ; break ;
            case "pgsql"    : $this->functions = new TableDaoPdo( self::$connect ) ; break ;
            case "sqlite"   : $this->functions = new TableDaoPdo( self::$connect ) ; break ;
            case "xml"      : $this->functions = new TableDaoXml( self::$connect ) ; break ;
            default: throw new \Exception() ;
            // var_dump( $this->functions ) ;
        } 

    }

    protected function readAll( $this ) : void
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $args ) ;

        $this->objectIDs = $this->functions->readAll( ...$args ) ;
        reset( $this->objectIDs ) ;
    }

    public function count()   : int    { return count( $this->objectIDs ) ; }
    public function reset()   : $class | false { return $this->testOne( (int)   reset( $this->objectIDs ) ) ; }
    public function prev()    : $class | false { return $this->testOne( (int)    prev( $this->objectIDs ) ) ; }
    public function current() : $class | false { return $this->testOne( (int) current( $this->objectIDs ) ) ; }
    public function next()    : $class | false { return $this->testOne( (int)    next( $this->objectIDs ) ) ; }
    public function end()     : $class | false { return $this->testOne( (int)     end( $this->objectIDs ) ) ; }

    public function getOne( int $index ) { return new $class( $index ) ; }
    public function getAll() : array 
    {
        $allObjects = [] ;
        foreach ( $this->objectIDs as $oneID )
        {
            $allObjects[] = new $class( (int) $oneID ) ;
        }
    return  $allObjects ; }
    
    private function testOne( int $index )
    {
        try {
            return $this->getOne( $index ) ;
        } catch ( \Exception ) { return false ; }
    }

    public function getData()   { return $this->values ; }
    public function getValues() { return array_values( $this->values ) ; }
    public function getKeys()   { return array_keys( $this->values ) ; }

}

?>
