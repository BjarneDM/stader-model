<?php namespace stader\model ;

class AreasDao extends Setup
{
    private   $functions = null ;
    protected $objectIDs = [] ;
    
    function __construct ()
    {   // echo 'class AreasDao extends Setup __construct' . \PHP_EOL ;

        parent::__construct( 'data' ) ;

        switch ( self::$connect->getType() )
        {
            case "mysql"    : $this->functions = new AreaDaoPdo( self::$connect ) ; break ;
            case "pgsql"    : $this->functions = new AreaDaoPdo( self::$connect ) ; break ;
            case "sqlite"   : $this->functions = new AreaDaoPdo( self::$connect ) ; break ;
            case "xml"      : $this->functions = new AreaDaoXml( self::$connect ) ; break ;
            default: throw new \Exception() ;
            // var_dump( $this->functions ) ;
        } 

    }

    protected function readAll( ...$args ) : void
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $args ) ;

        $this->objectIDs = $this->functions->readAll( ...$args ) ;
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
    {
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
    
}

?>
