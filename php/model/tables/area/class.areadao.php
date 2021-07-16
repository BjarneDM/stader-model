<?php namespace stader\model ;

abstract class AreaDao extends Setup
{
    private   $functions = null ;
    protected $values    = [] ;
    protected $valuesOld = [] ;
    
    public function __construct ( string $dbType )
    {   // echo 'class AreaDao estends Setup __construct' . \PHP_EOL ;

        parent::__construct( $dbType ) ;

        switch ( self::$connect->getType() )
        {
            case "mysql"    : $this->functions = new TableDaoPdo( self::$connect , Area::$class ) ; break ;
            case "pgsql"    : $this->functions = new TableDaoPdo( self::$connect , Area::$class ) ; break ;
            case "sqlite"   : $this->functions = new TableDaoPdo( self::$connect , Area::$class ) ; break ;
            case "xml"      : $this->functions = new TableDaoXml( self::$connect , Area::$class ) ; break ;
            default: throw new \Exception() ;
            // var_dump( $this->functions ) ;
        } 

    }

    protected function create( Area $object ) 
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $array ) ;

            return $this->functions->create( $object ) ; }

    protected function read( Area $object )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $args ) ;

            $this->values = $this->functions->readOne( $object ) ; }

    protected function update( Area $object )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $object ) ;

            $rowCount = $this->functions->update( $object , array_diff( $this->values , $this->valuesOld ) ) ;
        return $rowCount ; }

    protected function deleteThis( Area $object )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;

            $rowCount = $this->functions->delete( $object ) ;
            $this->values = null ;
        return $rowCount ; }

    public function getData()   { return $this->values ; }
    public function getValues() { return array_values( $this->values ) ; }
    public function getKeys()   { return array_keys( $this->values ) ; }

}

?>
