<?php namespace stader\model ;

abstract class AreasDao extends Setup
{
    private   $functions = null ;
    protected $objectIDs = [] ;
    protected $values    = [] ;
    
    public function __construct ( $dbType )
    {   // echo 'class AreasDao extends Setup __construct' . \PHP_EOL ;

        parent::__construct( $dbType ) ;

        switch ( self::$connect->getType() )
        {
            case "mysql"    : $this->functions = new TableDaoPdo( self::$connect , Areas::$class ) ; break ;
            case "pgsql"    : $this->functions = new TableDaoPdo( self::$connect , Areas::$class ) ; break ;
            case "sqlite"   : $this->functions = new TableDaoPdo( self::$connect , Areas::$class ) ; break ;
            case "xml"      : $this->functions = new TableDaoXml( self::$connect , Areas::$class ) ; break ;
            default: throw new \Exception() ;
            // var_dump( $this->functions ) ;
        } 

    }

    protected function readAll( Areas $object ) : void
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $object ) ;

        $this->objectIDs = $this->functions->readAll( $object ) ;
        reset( $this->objectIDs ) ;
    }

    public function count()   : int   { return count( $this->objectIDs ) ; }
    public function reset()   : Area | false  { return $this->testOne( (int)   reset( $this->objectIDs ) ) ; }
    public function prev()    : Area | false  { return $this->testOne( (int)    prev( $this->objectIDs ) ) ; }
    public function current() : Area | false  { return $this->testOne( (int) current( $this->objectIDs ) ) ; }
    public function next()    : Area | false  { return $this->testOne( (int)    next( $this->objectIDs ) ) ; }
    public function end()     : Area | false  { return $this->testOne( (int)     end( $this->objectIDs ) ) ; }

    public function getOne( int $index ) : Area { return new Area( $index ) ; }

    public function getAll() : array 
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;

        $allObjects = [] ;
        foreach ( $this->objectIDs as $oneID )
        {
            $allObjects[] = new Area( (int) $oneID ) ;
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
