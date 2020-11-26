<?php namespace stader\model ;

require_once( __dir__ . '/class.placelogdaopdo.php' ) ;
require_once( dirname( __file__ , 2 ) . '/connect/class.setuplog.php' ) ;

class PlaceLogsDao extends SetupLog
{
    private   $functions = null ;
    protected $placelogs = [] ;
    
    function __construct ()
    {   // echo 'class PlaceLogsDao extends SetupLog __construct' . \PHP_EOL ;

        parent::__construct() ;

        switch ( self::$connect->getType() )
        {
            case "mysql"    : $this->functions = new PlaceLogDaoPdo( self::$connect ) ; break ;
            case "pgsql"    : $this->functions = new PlaceLogDaoPdo( self::$connect ) ; break ;
            case "sqlite"   : $this->functions = new PlaceLogDaoPdo( self::$connect ) ; break ;
            case "xml"      : $this->functions = new PlaceLogDaoXml( self::$connect ) ; break ;
            default: throw new \Exception() ;
            // var_dump( $this->functions ) ;
        } 

    }

    protected function readAll( ...$args )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $args ) ;

        $placeIds = $this->functions->readAll( ...$args ) ;
        foreach ( $placeIds as $placeId )
        {
            $this->placelogs[] = new PlaceLog( (int) $placeId ) ;
        }
        reset( $this->placelogs ) ;
    }

    public function count()     { return   count( $this->placelogs ) ; }
    public function reset()     { return   reset( $this->placelogs ) ; }
    public function prev()      { return    prev( $this->placelogs ) ; }
    public function current()   { return current( $this->placelogs ) ; }
    public function next()      { return    next( $this->placelogs ) ; }
    public function end()       { return     end( $this->placelogs ) ; }

    public function getPlaceLog( int $index ) { return $this->placelogs[ $index ] ; }
    public function getOne( int $index ) { return $this->getPlaceLog( $index ) ; }

    public function getPlaceLogs() { return $this->placelogs ; }
    public function getAll() { return $this->getPlaceLogs() ; }

}

?>
