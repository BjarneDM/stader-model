<?php namespace stader\model ;

abstract class ObjectDao extends Setup
{
    private   $functions = null ;
    protected $values    = [] ;
    protected $valuesOld = [] ;
    protected $class     = '' ;
    
    function __construct ( $dbType )
    {   // echo 'abstract class ObjectDao extends Setup __construct' . \PHP_EOL ;

        parent::__construct( $dbType ) ;

        switch ( self::$connect->getType() )
        {
            case "mysql"    : $this->functions = new TableDaoPdo( self::$connect , $this->class ) ; break ;
            case "pgsql"    : $this->functions = new TableDaoPdo( self::$connect , $this->class ) ; break ;
            case "sqlite"   : $this->functions = new TableDaoPdo( self::$connect , $this->class ) ; break ;
            case "xml"      : $this->functions = new TableDaoXml( self::$connect , $this->class ) ; break ;
            default: throw new \Exception() ;
            // var_dump( $this->functions ) ;
        } 

    }

    protected function create( $this ) 
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $array ) ;

            return $this->functions->create( $array ) ; }

    protected function read( $this )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $args ) ;

            $this->values = $this->functions->readOne( ...$args ) ; }

    protected function update( $this ) 
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( [ $key , $value ] ) ;

            $oldValue = $this->values[ $key ] ;
            $rowCount = $this->functions->update( $this->values['id'] , $key , $value ) ;
        return [ $rowCount , $oldValue ] ; }

    public function delete( $this )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;

            $rowCount = $this->functions->delete( $this ) ;
            $this->values = null ;
        return $rowCount ; }

    public function getData()   { return $this->values ; }
    public function getValues() { return array_values( $this->values ) ; }
    public function getKeys()   { return array_keys( $this->values ) ; }

}

?>
