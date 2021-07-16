<?php namespace stader\model ;

require_once( __dir__ . '/class.flagdaopdo.php' ) ;
require_once( dirname( __file__ , 2 ) . '/connect/class.setup.php' ) ;

class FlagDao extends Setup
{
    private $functions = null ;
    protected $values = [] ;
    
    function __construct ()
    {   // echo 'class FlagDao estends Setup __construct' . \PHP_EOL ;

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
            $rowCount = $this->functions->update( $this->values['flag_id'] , $key , $value ) ;
        return [ $rowCount , $oldValue ] ; }

    public function delete()
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;

            $rowCount = $this->functions->delete( $this->values['flag_id'] ) ;
            $this->values = null ;
        return $rowCount ; }

    public function getData()   { return $this->values ; }
    public function getValues() { return array_values( $this->values ) ; }
    public function getKeys()   { return array_keys( $this->values ) ; }

}

?>
