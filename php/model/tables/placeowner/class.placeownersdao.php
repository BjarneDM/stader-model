<?php namespace stader\model ;

require_once( __dir__ . '/class.placeownerdaopdo.php' ) ;
require_once( dirname( __file__ , 2 ) . '/connect/class.setup.php' ) ;

class PlaceOwnersDao extends Setup
{
    private   $functions = null ;
    protected $placeowners = [] ;
    
    function __construct ()
    {   // echo 'class PlaceOwnersDao extends Setup __construct' . \PHP_EOL ;

        parent::__construct() ;

        switch ( self::$connect->getType() )
        {
            case "mysql"    : $this->functions = new PlaceOwnerDaoPdo( self::$connect ) ; break ;
            case "pgsql"    : $this->functions = new PlaceOwnerDaoPdo( self::$connect ) ; break ;
            case "sqlite"   : $this->functions = new PlaceOwnerDaoPdo( self::$connect ) ; break ;
            case "xml"      : $this->functions = new PlaceOwnerDaoXml( self::$connect ) ; break ;
            default: throw new \Exception() ;
            // var_dump( $this->functions ) ;
        } 

    }

    protected function readAll( ...$args )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $args ) ;

        $placeownerIds = $this->functions->readAll( ...$args ) ;
        foreach ( $placeownerIds as $placeownerId )
        {
            $this->placeowners[] = new PlaceOwner( (int) $placeownerId ) ;
        }
        reset( $this->placeowners ) ;
    }

    public function count()     { return   count( $this->placeowners ) ; }
    public function reset()     { return   reset( $this->placeowners ) ; }
    public function prev()      { return    prev( $this->placeowners ) ; }
    public function current()   { return current( $this->placeowners ) ; }
    public function next()      { return    next( $this->placeowners ) ; }
    public function end()       { return     end( $this->placeowners ) ; }

    public function getPlaceOwner( int $index ) { return $this->placeowners[ $index ] ; }
    public function getOne( int $index ) { return $this->getPlaceOwner( $index ) ; }

    public function getPlaceOwners() { return $this->placeowners ; }
    public function getAll() { return $this->getPlaceOwners() ; }

}

?>
