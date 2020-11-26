<?php namespace stader\model ;

require_once( __dir__ . '/class.ticketgroupdaopdo.php' ) ;
require_once( dirname( __file__ , 2 ) . '/connect/class.setup.php' ) ;

class TicketsGroupsDao extends Setup
{
    private   $functions = null ;
    protected $ticketsgroups = [] ;
    
    function __construct ()
    {   // echo 'class TicketsGroupsDao extends Setup __construct' . \PHP_EOL ;

        parent::__construct() ;

        switch ( self::$connect->getType() )
        {
            case "mysql"    : $this->functions = new TicketGroupDaoPdo( self::$connect ) ; break ;
            case "pgsql"    : $this->functions = new TicketGroupDaoPdo( self::$connect ) ; break ;
            case "sqlite"   : $this->functions = new TicketGroupDaoPdo( self::$connect ) ; break ;
            case "xml"      : $this->functions = new TicketGroupDaoXml( self::$connect ) ; break ;
            default: throw new \Exception() ;
            // var_dump( $this->functions ) ;
        } 

    }

    protected function readAll( ...$args )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $args ) ;

        $ticketssGroupsIds = $this->functions->readAll( ...$args ) ;
        foreach ( $ticketssGroupsIds as $ticketGroupId )
        {
            $this->ticketsgroups[] = new TicketGroup( (int) $ticketGroupId ) ;
        }
        reset( $this->ticketsgroups ) ;
    }

    public function count()     { return   count( $this->ticketsgroups ) ; }
    public function reset()     { return   reset( $this->ticketsgroups ) ; }
    public function prev()      { return    prev( $this->ticketsgroups ) ; }
    public function current()   { return current( $this->ticketsgroups ) ; }
    public function next()      { return    next( $this->ticketsgroups ) ; }
    public function end()       { return     end( $this->ticketsgroups ) ; }

    public function getTicketGroup( int $index ) { return $this->ticketsgroups[ $index ] ; }
    public function getOne( int $index ) { return $this->getTicketGroup( $index ) ; }

    public function getTicketsGroups() { return $this->ticketsgroups ; }
    public function getAll() { return $this->getTicketsGroups() ; }

}

?>
