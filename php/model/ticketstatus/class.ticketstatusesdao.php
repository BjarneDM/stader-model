<?php namespace stader\model ;

require_once( __dir__ . '/class.ticketstatusdaopdo.php' ) ;
require_once( dirname( __file__ , 2 ) . '/connect/class.setup.php' ) ;

class TicketStatusesDao extends Setup
{
    private   $functions = null ;
    protected $ticketstatuses = [] ;
    
    function __construct ()
    {   // echo 'class TicketStatusesDao extends Setup __construct' . \PHP_EOL ;

        parent::__construct() ;

        switch ( self::$connect->getType() )
        {
            case "mysql"    : $this->functions = new TicketStatusDaoPdo( self::$connect ) ; break ;
            case "pgsql"    : $this->functions = new TicketStatusDaoPdo( self::$connect ) ; break ;
            case "sqlite"   : $this->functions = new TicketStatusDaoPdo( self::$connect ) ; break ;
            case "xml"      : $this->functions = new TicketStatusDaoXml( self::$connect ) ; break ;
            default: throw new \Exception() ;
            // var_dump( $this->functions ) ;
        } 

    }

    protected function readAll( ...$args )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $args ) ;

        $ticketstatusIds = $this->functions->readAll( ...$args ) ;
        foreach ( $ticketstatusIds as $ticketstatusId )
        {
            $this->ticketstatuses[] = new TicketStatus( (int) $ticketstatusId ) ;
        }
        reset( $this->ticketstatuses ) ;
    }

    public function count()     { return   count( $this->ticketstatuses ) ; }
    public function reset()     { return   reset( $this->ticketstatuses ) ; }
    public function prev()      { return    prev( $this->ticketstatuses ) ; }
    public function current()   { return current( $this->ticketstatuses ) ; }
    public function next()      { return    next( $this->ticketstatuses ) ; }
    public function end()       { return     end( $this->ticketstatuses ) ; }

    public function getTicketStatus( int $index ) { return $this->ticketstatuses[ $index ] ; }
    public function getOne( int $index ) { return $this->getTicketStatus( $index ) ; }

    public function getTicketStatuses() { return $this->ticketstatuses ; }
    public function getAll() { return $this->getTicketStatuses() ; }

}

?>
