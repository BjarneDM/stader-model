<?php namespace stader\model ;

require_once( __dir__ . '/class.ticketlogdaopdo.php' ) ;
require_once( dirname( __file__ , 2 ) . '/connect/class.setuplog.php' ) ;

class TicketLogDao extends SetupLog
{
    private $functions = null ;
    protected $values = [] ;
    
    function __construct ()
    {   // echo 'class TicketDao extends SetupLog __construct' . \PHP_EOL ;

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

    protected function create( Array $array ) 
        { return $this->functions->create( $array ) ; }

    protected function read( ...$args )
        { $this->values = $this->functions->readOne( ...$args ) ; }

    public function getData()   { return $this->values ; }
    public function getValues() { return array_values( $this->values ) ; }
    public function getKeys()   { return array_keys( $this->values ) ; }

}

?>
