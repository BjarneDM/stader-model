<?php namespace stader\model ;

require_once( __dir__ . '/class.flagdaopdo.php' ) ;
require_once( dirname( __file__ , 2 ) . '/connect/class.setup.php' ) ;

class FlagsDao extends Setup
{
    private   $functions = null ;
    protected $flags = [] ;
    
    function __construct ()
    {   // echo 'class FlagsDao extends Setup __construct' . \PHP_EOL ;

        parent::__construct() ;

        switch ( self::$connect->getType() )
        {
            case "mysql"    : $this->functions = new FlagDaoPdo( self::$connect ) ; break ;
            case "pgsql"    : $this->functions = new FlagDaoPdo( self::$connect ) ; break ;
            case "sqlite"   : $this->functions = new FlagDaoPdo( self::$connect ) ; break ;
            case "xml"      : $this->functions = new FlagDaoXml( self::$connect ) ; break ;
            default: throw new \Exception() ;
            // var_dump( $this->functions ) ;
        } 

    }

    protected function readAll()
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $args ) ;

        $flagIds = $this->functions->readAll() ;
        foreach ( $flagIds as $flagId )
        {
            $this->flags[] = new Flag( (int) $flagId ) ;
        }
        reset( $this->flags ) ;
    }

    public function count()     { return   count( $this->flags ) ; }
    public function reset()     { return   reset( $this->flags ) ; }
    public function prev()      { return    prev( $this->flags ) ; }
    public function current()   { return current( $this->flags ) ; }
    public function next()      { return    next( $this->flags ) ; }
    public function end()       { return     end( $this->flags ) ; }

    public function getFlag( int $index ) { return $this->flags[ $index ] ; }
    public function getOne( int $index ) { return $this->getFlag( $index ) ; }

    public function getFlags() { return $this->flags ; }
    public function getAll() { return $this->getFlags() ; }

}

?>
