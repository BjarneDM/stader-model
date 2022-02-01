<?php namespace Stader\Model ;

require_once( __dir__ . '/class.ticketdaopdo.php' ) ;
require_once( dirname( __file__ , 2 ) . '/connect/class.setup.php' ) ;

class TicketDao extends Setup
{
    private $functions = null ;
    protected $values = [] ;
    
    function __construct ()
    {   // echo 'class TicketDao extends Setup __construct' . \PHP_EOL ;

        parent::__construct() ;

        switch ( self::$connect->getType() )
        {
            case "mysql"    : $this->functions = new TicketDaoPdo( self::$connect ) ; break ;
            case "pgsql"    : $this->functions = new TicketDaoPdo( self::$connect ) ; break ;
            case "sqlite"   : $this->functions = new TicketDaoPdo( self::$connect ) ; break ;
            case "xml"      : $this->functions = new TicketDaoXml( self::$connect ) ; break ;
            default: throw new \Exception() ;
            // var_dump( $this->functions ) ;
        } 

    }

    protected function create( Array $array ) 
        { return $this->functions->create( $array ) ; }

    protected function read( ...$args )
        { $this->values = $this->functions->readOne( ...$args ) ; }

    protected function update( string $key , $value ) 
        {
            $oldValue = $this->values[ $key ] ;
            $this->values['lastupdatetime'] = $this->functions->update( $this->values['ticket_id'] , $key , $value ) ;
        return $oldValue ; }

    public function delete()
        {
            $rowCount = $this->functions->delete( $this->values['ticket_id'] ) ;
            $this->values = null ;
        return $rowCount ; }

    public function getData()   { return $this->values ; }
    public function getValues() { return array_values( $this->values ) ; }
    public function getKeys()   { return array_keys( $this->values ) ; }

}

?>
