<?php namespace stader\model ;

require_once( __dir__ . '/class.areadaopdo.php' ) ;
require_once( dirname( __file__ , 2 ) . '/connect/class.setup.php' ) ;

class AreasDao extends Setup
{
    private   $functions = null ;
    protected $areas = [] ;
    
    function __construct ()
    {   // echo 'class AreasDao extends Setup __construct' . \PHP_EOL ;

        parent::__construct() ;

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

    protected function readAll( ...$args )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $args ) ;

        $areaIds = $this->functions->readAll( ...$args ) ;
        foreach ( $areaIds as $areaId )
        {
            $this->areas[] = new Area( (int) $areaId ) ;
        }
        reset( $this->areas ) ;
    }

    public function count()     { return   count( $this->areas ) ; }
    public function reset()     { return   reset( $this->areas ) ; }
    public function prev()      { return    prev( $this->areas ) ; }
    public function current()   { return current( $this->areas ) ; }
    public function next()      { return    next( $this->areas ) ; }
    public function end()       { return     end( $this->areas ) ; }

    public function getArea( int $index ) { return $this->areas[ $index ] ; }
    public function getOne( int $index ) { return $this->getArea( $index ) ; }
    
    public function getAreas() { return $this->areas ; }
    public function getAll() { return $this->getAreas() ; }
    
}

?>
