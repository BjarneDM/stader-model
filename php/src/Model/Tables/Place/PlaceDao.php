<?php namespace Stader\Model ;

require_once( __dir__ . '/class.placedaopdo.php' ) ;
require_once( dirname( __file__ , 2 ) . '/connect/class.setup.php' ) ;

class PlaceDao extends Setup
{
    private $functions = null ;
    protected $values = [] ;
    
    function __construct ()
    {   // echo 'class PlaceDao extends Setup __construct' . \PHP_EOL ;

        parent::__construct() ;

        switch ( self::$connect->getType() )
        {
            case "mysql"    : $this->functions = new PlaceDaoPdo( self::$connect ) ; break ;
            case "pgsql"    : $this->functions = new PlaceDaoPdo( self::$connect ) ; break ;
            case "sqlite"   : $this->functions = new PlaceDaoPdo( self::$connect ) ; break ;
            case "xml"      : $this->functions = new PlaceDaoXml( self::$connect ) ; break ;
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
        $this->values['lastchecked'] = $this->functions->update( $this->values['place_id'] , $key , $value ) ;
    return $oldValue ; }

    public function delete()
    {
        $rowCount = $this->functions->delete( $this->values['place_id'] ) ;
        $this->values = null ;
    return $rowCount ; }

    public function getData()   { return $this->values ; }
    public function getValues() { return array_values( $this->values ) ; }
    public function getKeys()   { return array_keys( $this->values ) ; }

    public function setChecked( ...$args )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $args ) ;

        switch ( strtolower( gettype( $args[0] ) ) )
        {
            case 'null' :
                $this->values['lastchecked'] = new \DateTime() ;
                break ;
            case 'datetime' :
                $this->values['lastchecked'] = $args[0] ;
                break ;
            default :
                throw new \Exception( gettype( $args[0] ) . " : forkert input type [integer,array]" ) ;
                break ;
            
        }

        $this->functions->setChecked( $this->values['place_id'] , $this->values['lastchecked'] ) ; 
    }
}

?>
