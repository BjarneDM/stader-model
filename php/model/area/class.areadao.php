<?php namespace stader\model ;

require_once( __dir__ . '/class.areadaopdo.php' ) ;
require_once( dirname( __file__ , 2 ) . '/connect/class.setup.php' ) ;

class AreaDao extends Setup
{
    private $functions = null ;
    protected $values = [] ;
    
    function __construct ()
    {   // echo 'class AreaDao estends Setup __construct' . \PHP_EOL ;

        parent::__construct() ;

        switch ( self::$connect->getType() )
        {
            case "mysql"    : $this->functions = new AreaDaoPdo( self::$connect ) ; break ;
            case "pgsql"    : $this->functions = new AreaDaoPdo( self::$connect ) ; break ;
            case "sqlite"   : $this->functions = new AreaDaoPdo( self::$connect ) ; break ;
            case "xml"      : $this->functions = new AreaDaoXml( self::$connect ) ; break ;
            default: throw new \Exception() ;
            // var_dump( $this->functions ) ;
        } 

    }

    protected function create( Array $array ) 
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $array ) ;

            return $this->functions->create( $array ) ; }

    protected function read( ...$args )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $args ) ;

            $this->values = $this->functions->readOne( ...$args ) ; }

    protected function update( string $key , $value ) 
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( [ $key , $value ] ) ;

            $oldValue = $this->values[ $key ] ;
            $rowCount = $this->functions->update( $this->values['area_id'] , $key , $value ) ;
        return [ $rowCount , $oldValue ] ; }

    public function delete()
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;

            $rowCount = $this->functions->delete( $this->values['area_id'] ) ;
            $this->values = null ;
        return $rowCount ; }

    public function getData()   { return $this->values ; }
    public function getValues() { return array_values( $this->values ) ; }
    public function getKeys()   { return array_keys( $this->values ) ; }

}

?>
