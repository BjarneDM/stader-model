<?php namespace stader\model ;

require_once( __dir__ . '/class.ticketgroupdaopdo.php' ) ;
require_once( dirname( __file__ , 2 ) . '/group/class.group.php' ) ;
require_once( dirname( __file__ , 2 ) . '/ticket/class.ticket.php' ) ;
require_once( dirname( __file__ , 2 ) . '/connect/class.setup.php' ) ;

class TicketGroupDao extends Setup
{
    private $functions = null ;
    protected $values = [] ;
    
    function __construct ()
    {   // echo 'class TicketGroupDao extends Setup __construct' . \PHP_EOL ;

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

    protected function create( Array $array ) 
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $array ) ;

        $ids = [] ;
        foreach ( $array as $key => $value )
        {
            if ( in_array( $key , [ 'hader' ] ) )
            {
                $ticket  = new  Ticket( $key , $value ) ;
                $ids['ticket_id'] = $ticket->getData()['ticket_id'] ;
            }
            if ( in_array( $key , [ 'ticket_id' ] ) )
            {
                $ticket  = new  Ticket( $value ) ;
                $ids['ticket_id'] = $ticket->getData()['ticket_id'] ;
            }
            if ( in_array( $key , [ 'name' ] ) )
            {
                $group = new Group( $key , $value ) ;
                $ids['group_id'] = $group->getData()['group_id'] ;
            }
            if ( in_array( $key , [ 'group_id' ] ) )
            {
                $group = new Group( $value ) ;
                $ids['group_id'] = $group->getData()['group_id'] ;
            }
            unset( $ticket , $group) ;
        } unset( $key , $value ) ;

        return $this->functions->create( $ids ) ;
    }

    protected function read( ...$args )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $args ) ;

        $this->values = $this->functions->readOne( ...$args ) ;
    }

    protected function update( string $key , $value ) 
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( [ $key , $value ] ) ;

        $oldValue = $this->values[ $key ] ;
        $rowCount = $this->functions->update( $this->values['ticket_group_id'] , $key , $value ) ;
    return [ $rowCount , $oldValue ] ; }

    public function delete()
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;

        $rowCount = $this->functions->delete( $this->values['ticket_group_id'] ) ;
        $this->values = null ;
    return $rowCount ; }

    public function getData()   { return $this->values ; }
    public function getValues() { return array_values( $this->values ) ; }
    public function getKeys()   { return array_keys( $this->values ) ; }

}

?>
