<?php namespace stader\model ;

require_once( __dir__ . '/class.ticketlogdaopdo.php' ) ;
require_once( dirname( __file__ , 2 ) . '/connect/class.setuplog.php' ) ;

class TicketLogsDao extends SetupLog
{
    private   $functions = null ;
    protected $ticketlogs = [] ;
    
    function __construct ()
    {   // echo 'class TicketLogsDao extends SetupLog __construct' . \PHP_EOL ;

        parent::__construct() ;

        switch ( self::$connect->getType() )
        {
            case "mysql"    : $this->functions = new TicketLogDaoPdo( self::$connect ) ; break ;
            case "pgsql"    : $this->functions = new TicketLogDaoPdo( self::$connect ) ; break ;
            case "sqlite"   : $this->functions = new TicketLogDaoPdo( self::$connect ) ; break ;
            case "xml"      : $this->functions = new TicketLogDaoXml( self::$connect ) ; break ;
            default: throw new \Exception() ;
            // var_dump( $this->functions ) ;
        } 

    }

    protected function readAll( ...$args )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $args ) ;

        $ticketIds = $this->functions->readAll( ...$args ) ;
        foreach ( $ticketIds as $ticketId )
        {
            $this->ticketlogs[] = new TicketLog( (int) $ticketId ) ;
        }
        reset( $this->ticketlogs ) ;
    }

    public function count()     { return   count( $this->ticketlogs ) ; }
    public function reset()     { return   reset( $this->ticketlogs ) ; }
    public function prev()      { return    prev( $this->ticketlogs ) ; }
    public function current()   { return current( $this->ticketlogs ) ; }
    public function next()      { return    next( $this->ticketlogs ) ; }
    public function end()       { return     end( $this->ticketlogs ) ; }

    public function getTicketLog( int $index ) { return $this->ticketlogs[ $index ] ; }
    public function getOne( int $index ) { return $this->getTicketLog( $index ) ; }

    public function getTicketLogs() { return $this->ticketlogs ; }
    public function getAll() { return $this->getTicketLogs() ; }

}

?>
