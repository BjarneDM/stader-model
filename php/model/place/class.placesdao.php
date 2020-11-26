<?php namespace stader\model ;

require_once( __dir__ . '/class.placedaopdo.php' ) ;
require_once( dirname( __file__ , 2 ) . '/connect/class.setup.php' ) ;

class PlacesDao extends Setup
{
    private   $functions = null ;
    protected $places = [] ;
    
    function __construct ()
    {   // echo 'class PlacesDao extends Setup __construct' . \PHP_EOL ;

        parent::__construct() ;

        switch ( self::$connect->getType() )
        {
            case "mysql"    : $this->functions = new PlaceDaoPdo( self::$connect ) ; break ;
            case "pgsql"    : $this->functions = new PlaceDaoPdo( self::$connect ) ; break ;
            case "sqlite"   : $this->functions = new PlaceDaoPdo( self::$connect ) ; break ;
            case "xml"      : $this->functions = new PlaceDaoXml( self::$connect ) ; break ;
            default: throw new \Exception() ;
            // var_dump( $this->functions ) ;
        } 

    }

    protected function readAll( ...$args )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        $placeIds = $this->functions->readAll( ...$args ) ;
        foreach ( $placeIds as $placeId )
        {
            $this->places[] = new Place( (int) $placeId ) ;
        }
        reset( $this->places ) ;
    }

    public function count()     { return   count( $this->places ) ; }
    public function reset()     { return   reset( $this->places ) ; }
    public function prev()      { return    prev( $this->places ) ; }
    public function current()   { return current( $this->places ) ; }
    public function next()      { return    next( $this->places ) ; }
    public function end()       { return     end( $this->places ) ; }

    public function getPlace( int $index ) { return $this->places[ $index ] ; }
    public function getOne( int $index ) { return $this->getPlace( $index ) ; }

    public function getPlaces() { return $this->places ; }
    public function getAll() { return $this->getPlaces() ; }

}

?>
