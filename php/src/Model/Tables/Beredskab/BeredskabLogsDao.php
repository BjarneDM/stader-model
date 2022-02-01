<?php namespace Stader\Model ;

require_once( __dir__ . '/class.beredskablogdaopdo.php' ) ;
require_once( dirname( __file__ , 2 ) . '/connect/class.setuplog.php' ) ;

class BeredskabLogsDao extends SetupLog
{
    private   $functions = null ;
    protected $beredskablogs = [] ;
    
    function __construct ()
    {   // echo 'class BeredskabLogsDao extends SetupLog __construct' . \PHP_EOL ;

        parent::__construct() ;

        switch ( self::$connect->getType() )
        {
            case "mysql"    : $this->functions = new BeredskabLogDaoPdo( self::$connect ) ; break ;
            case "pgsql"    : $this->functions = new BeredskabLogDaoPdo( self::$connect ) ; break ;
            case "sqlite"   : $this->functions = new BeredskabLogDaoPdo( self::$connect ) ; break ;
            case "xml"      : $this->functions = new BeredskabLogDaoXml( self::$connect ) ; break ;
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
            $this->beredskablogs[] = new BeredskabLog( (int) $beredskabId ) ;
        }
        reset( $this->beredskablogs ) ;
    }

    public function count()     { return   count( $this->beredskablogs ) ; }
    public function reset()     { return   reset( $this->beredskablogs ) ; }
    public function prev()      { return    prev( $this->beredskablogs ) ; }
    public function current()   { return current( $this->beredskablogs ) ; }
    public function next()      { return    next( $this->beredskablogs ) ; }
    public function end()       { return     end( $this->beredskablogs ) ; }

    public function getBeredskabLog( int $index ) { return $this->beredskablogs[ $index ] ; }
    public function getOne( int $index ) { return $this>getBeredskabLog( $index ) ; }

    public function getBeredskabLogs() { return $this->beredskablogs ; }
    public function getAll() { return $this->getBeredskabLogs() ; }

}

?>
