<?php namespace Stader\Model ;

require_once( __dir__ . '/class.beredskabdaopdo.php' ) ;
require_once( dirname( __file__ , 2 ) . '/connect/class.setup.php' ) ;

class BeredskabsDao extends Setup
{
    private   $functions = null ;
    protected $beredskabs = [] ;
    
    function __construct ()
    {   // echo 'class BeredskabsDao extends Setup __construct' . \PHP_EOL ;

        parent::__construct() ;

        switch ( self::$connect->getType() )
        {
            case "mysql"    : $this->functions = new BeredskabDaoPdo( self::$connect ) ; break ;
            case "pgsql"    : $this->functions = new BeredskabDaoPdo( self::$connect ) ; break ;
            case "sqlite"   : $this->functions = new BeredskabDaoPdo( self::$connect ) ; break ;
            case "xml"      : $this->functions = new BeredskabDaoXml( self::$connect ) ; break ;
            default: throw new \Exception() ;
            // var_dump( $this->functions ) ;
        } 

    }

    protected function readAll( ...$args )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $args ) ;

        $beredskabIds = $this->functions->readAll( ...$args ) ;
        foreach ( $beredskabIds as $beredskabId )
        {
            $this->beredskabs[] = new Beredskab( (int) $beredskabId ) ;
        }
        reset( $this->beredskabs ) ;
    }

    public function count()     { return   count( $this->beredskabs ) ; }
    public function reset()     { return   reset( $this->beredskabs ) ; }
    public function prev()      { return    prev( $this->beredskabs ) ; }
    public function current()   { return current( $this->beredskabs ) ; }
    public function next()      { return    next( $this->beredskabs ) ; }
    public function end()       { return     end( $this->beredskabs ) ; }

    public function getBeredskab( int $index ) { return $this->beredskabs[ $index ] ; }
    public function getOne( int $index ) { return $this->getBeredskab( $index ) ; }

    public function getBeredskabs() { return $this->beredskabs ; }
    public function getAll() { return $this->getBeredskabs() ; }

}

?>
